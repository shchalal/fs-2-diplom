<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\MovieSession;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class PaymentController extends Controller
{   
    public function index(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:movie_sessions,id',
            'seats'      => 'required',
        ]);

        $session = MovieSession::with(['movie', 'hall', 'hall.price'])->findOrFail($request->session_id);

        $seatIds = json_decode($request->seats, true);
        if (!is_array($seatIds)) {
            return back()->withErrors('Некорректные данные о местах');
        }

        $seats = \App\Models\Seat::whereIn('id', $seatIds)->get();

        return view('client.payment', [
            'session' => $session,
            'seats'   => $seats,
            'seatIds' => $seatIds,
        ]);
    }

    public function store(Request $request)
    { 
         
        $request->validate([
            'session_id' => 'required|exists:movie_sessions,id',
            'seats'      => 'required',
        ]);

        $session = MovieSession::with('hall')->findOrFail($request->session_id);
        $seatIds = json_decode($request->seats, true);

        if (!is_array($seatIds)) {
            return back()->withErrors('Некорректные данные о местах');
        }

        
        $taken = Ticket::where('session_id', $session->id)
            ->whereIn('seat_id', $seatIds)
            ->exists();

        if ($taken) {
            return back()->withErrors('Некоторые места уже заняты.');
        }

     
        $orderId = Str::upper(Str::random(10));

        foreach ($seatIds as $seatId) {

            $bookingCode = Str::upper(Str::random(8));

          
           $options = new QROptions([
                  'version'      => 5,
                    'eccLevel'     => QRCode::ECC_H,
                    'scale'        => 6,
                    'outputType'   => QRCode::OUTPUT_IMAGE_PNG,
                    'imageBase64'  => false,
                    'returnResource' => false,
                        ]);

            $data = "order=$orderId;seat=$seatId;code=$bookingCode";

            $qrBinary = (new QRCode($options))->render($data);
            $qrPath = "qr/{$bookingCode}.png";
            file_put_contents(storage_path("app/public/$qrPath"), $qrBinary);
           
          

         
            Ticket::create([
                'session_id'   => $session->id,
                'seat_id'      => $seatId,
                'order_id'     => $orderId,
                'booking_code' => $bookingCode,
                'qr_path'      => $qrPath,
            ]);
        }

        return redirect()->route('client.ticket', ['order' => $orderId]);
    }
}
