<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CinemaHall;
use App\Models\Seat;
use App\Models\Movie;  
use App\Models\MovieSession;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $halls = CinemaHall::with('price')->get();

        $allSeatsGroupedByHall = Seat::orderBy('row_number')
            ->orderBy('seat_number')
            ->get()
            ->groupBy('hall_id')
            ->map(fn ($group) => $group->values())
            ->toArray();

        $movies = Movie::all();   
        $sessions = MovieSession::with('movie', 'hall')->get();

        return view('admin.dashboard', [
            'halls' => $halls,
            'allSeatsGroupedByHall' => $allSeatsGroupedByHall,
            'sessions' => $sessions,
            'movies' => $movies,   
        ]);
    }
}


