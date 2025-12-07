<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\CinemaHall;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));

        
        $dates = collect();
        for ($i = 0; $i < 7; $i++) {
            $dates->push(Carbon::today()->addDays($i)->format('Y-m-d'));
        }

    
        $movies = Movie::whereHas('sessions', function ($q) use ($date) {
            $q->where('session_date', $date);
        })
            ->with(['sessions' => function ($q) use ($date) {
                $q->where('session_date', $date)->orderBy('start_time');
            }])
            ->get();

       
        $halls = CinemaHall::where('is_active', 1)->get();

        return view('client.home', [
            'movies' => $movies,
            'halls'  => $halls,
            'dates'  => $dates,
            'date'   => $date
        ]);
    }
}
