<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\CinemaHall;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
       
        $movies = Movie::with([
            'sessions' => function($q) {
                $q->orderBy('start_time');
            }
        ])->get();

       
        $halls = CinemaHall::where('is_active', 1)->get();

        return view('client.home', [
            'movies' => $movies,
            'halls'  => $halls,
        ]);
    }
}
