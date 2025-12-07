<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\MovieSession;
use App\Models\Seat;
use App\Models\Ticket;

class HallController extends Controller
{
    public function index($sessionId)
    {

        $session = MovieSession::with(['movie', 'hall'])
            ->findOrFail($sessionId);


        $seats = Seat::where('hall_id', $session->hall_id)
            ->orderBy('row_number')
            ->orderBy('seat_number')
            ->get();

    
        $takenSeats = Ticket::where('session_id', $sessionId)
            ->pluck('seat_id')
            ->toArray();

        return view('client.hall', [
            'session'     => $session,
            'seats'       => $seats,
            'takenSeats'  => $takenSeats,
            'prices'      => $session->hall->price,
        ]);
    }
}
