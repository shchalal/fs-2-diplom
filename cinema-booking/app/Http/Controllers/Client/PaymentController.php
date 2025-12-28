<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\MovieSession;
use App\Models\Seat;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class PaymentController extends Controller
{
    
    private function getSeatIds(Request $request): array
    {
        $seats = $request->input('seats');

        if (is_string($seats)) {
            $seats = json_decode($seats, true);
        }

        if (!is_array($seats)) {
            return [];
        }

       
        $seats = array_map('intval', $seats);
        $seats = array_values(array_filter($seats, fn($v) => $v > 0));

        return $seats;
    }

    public function index(Request $request)
    { 
        $request->validate([
            'session_id' => 'required|exists:movie_sessions,id',
            'seats'      => 'required',
            'date'       => 'required|date',
        ]);

        $seatIds = $this->getSeatIds($request);
        if (!is_array($seatIds) || empty($seatIds)) {
            return back()->withErrors('Некорректные данные о местах');
        }

        $session = MovieSession::with(['movie', 'hall.price'])
            ->findOrFail($request->session_id);

        $seats = Seat::whereIn('id', $seatIds)->get();
        $total = $session->totalPriceForSeats($seats);

        return view('client.payment', [
            'session' => $session,
            'seats'   => $seats,
            'seatIds' => $seatIds,
            'date'    => $request->date,
            'total'   => $total,
        ]);
    }

    public function store(Request $request)
    {  
        $request->validate([
            'session_id' => 'required|exists:movie_sessions,id',
            'seats'      => 'required',
            'date'       => 'required|date',
        ]);

        $seatIds = $this->getSeatIds($request);
        if (!is_array($seatIds) || empty($seatIds)) {
            return back()->withErrors('Некорректные данные о местах');
        }

        $session = MovieSession::with('hall.price')
            ->findOrFail($request->session_id);
     
        $taken = Ticket::where('session_id', $session->id)
            ->where('ticket_date', $request->date)
            ->whereIn('seat_id', $seatIds)
            ->exists();
        
        if ($taken) {
            return back()->withErrors([
                'seats' => 'Некоторые места уже заняты',
            ]);
        }

 
        $regularPrice = $session->price_regular
            ?? optional($session->hall->price)->regular_price
            ?? 0;

        $vipPrice = $session->price_vip
            ?? optional($session->hall->price)->vip_price
            ?? 0;

        $seats = Seat::whereIn('id', $seatIds)->get();
        
        $total = $session->totalPriceForSeats($seats);
        
        if ($total <= 0) {
            return back()->withErrors([
                'price' => 'Невозможно оформить бронирование с нулевой стоимостью',
            ]);
        }

        $orderId = Str::upper(Str::random(10));

        foreach ($seatIds as $seatId) {
            $bookingCode = Str::upper(Str::random(8));

            $options = new QROptions([
                'version'        => 5,
                'eccLevel'       => QRCode::ECC_H,
                'scale'          => 6,
                'outputType'     => QRCode::OUTPUT_IMAGE_PNG,
                'imageBase64'    => false,
                'returnResource' => false,
            ]);

            $data = "order={$orderId};seat={$seatId};code={$bookingCode}";
            $qrBinary = (new QRCode($options))->render($data);

            $qrPath = "qr/{$bookingCode}.png";

            if (app()->runningUnitTests()) {
                Storage::disk('public')->put($qrPath, $qrBinary);
            } else {
                file_put_contents(
                    storage_path("app/public/{$qrPath}"),
                    $qrBinary
                );
            }

            Ticket::create([
                'session_id'   => $session->id,
                'seat_id'      => $seatId,
                'ticket_date'  => $request->date,
                'order_id'     => $orderId,
                'booking_code' => $bookingCode,
                'qr_path'      => $qrPath,
            ]);
        }

        return redirect()->route('client.ticket', ['order' => $orderId]);
    }
}
