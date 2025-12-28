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
    $date = $request->get('date', now()->toDateString());

    $dates = collect();
    for ($i = 0; $i < 7; $i++) {
        $dates->push(now()->addDays($i)->toDateString());
    }

   
     $movies = Movie::with(['sessions' => function ($q) use ($date) {
        $q->whereDate('session_date', '>=', $date)
        ->orderBy('session_date')
        ->orderBy('start_time');
    }])->get();

    $halls = CinemaHall::where('is_active', 1)->get();

    return view('client.home', compact(
        'movies',
        'halls',
        'dates',
        'date'
    ));
}

}
