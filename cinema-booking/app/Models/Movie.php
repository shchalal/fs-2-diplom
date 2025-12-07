<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Movie extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'description',
        'poster_url',
        'duration',
        'age_limit',
    ];

     public function sessions()
    {
        return $this->hasMany(MovieSession::class, 'movie_id');
    }
}

