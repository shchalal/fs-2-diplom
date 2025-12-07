<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Ticket;

class TicketController extends Controller
{
    public function show($order)
    {
        $tickets = Ticket::where('order_id', $order)
            ->with(['session.movie', 'session.hall', 'seat'])
            ->get();

        if ($tickets->isEmpty()) {
            abort(404);
        }

        $session = $tickets->first()->session;

        return view('client.ticket', [
            'orderId' => $order,
            'tickets' => $tickets,
            'session' => $session,
        ]);
    }
}
