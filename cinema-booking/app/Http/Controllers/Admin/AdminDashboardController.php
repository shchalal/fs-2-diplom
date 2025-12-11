<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CinemaHall;
use App\Models\Seat;
use App\Models\Movie;  
use App\Models\MovieSession;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Models\Setting;

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
     public function openSales(Request $request)
        {
            $activeHalls = CinemaHall::where('is_active', 1)->count();

            if ($activeHalls === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Нет ни одного открытого зала — продажи нельзя открыть.'
                ], 422);
            }

            Setting::set('sales_open', true);

            return response()->json([
                'success' => true,
                'message' => 'Продажи успешно открыты!'
            ]);
        }
}


