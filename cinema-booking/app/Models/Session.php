<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $table = 'movie_sessions';

    protected $fillable = [
        'hall_id',
        'movie_id',
        'start_time',
        'end_time',
        'price_regular',
        'price_vip',
    ];
}

