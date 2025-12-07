<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'session_id',
        'seat_id',
        'booking_code',
        'qr_path',
    ];

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }
}
