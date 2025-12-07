<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\MovieSession;
use App\Models\Seat;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PaymentController extends Controller
{
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
            return back()->withErrors('Некоторые места уже заняты. Выберите другие.');
        }

       
        $orderId = Str::upper(Str::random(10));

        foreach ($seatIds as $seatId) {

            $bookingCode = Str::upper(Str::random(8));

            Ticket::create([
                'session_id'   => $session->id,
                'seat_id'      => $seatId,
                'order_id'     => $orderId,
                'booking_code' => $bookingCode,
            ]);
        }



        
        return redirect()->route('client.ticket', ['order' => $orderId]);
    }
}
