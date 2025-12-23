<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = [
        'session_id',
        'seat_id',
        'booking_code',
        'ticket_date', 
        'order_id',
        'qr_path',
    ];

    public function session()
{
    return $this->belongsTo(\App\Models\MovieSession::class, 'session_id');
}


    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }
}
