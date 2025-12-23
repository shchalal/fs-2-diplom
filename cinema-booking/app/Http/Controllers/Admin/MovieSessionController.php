<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MovieSession;
use Carbon\Carbon;
use App\Models\CinemaHall;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieSessionController extends Controller
{
    public function index()
    {
        $sessions = MovieSession::with(['hall', 'movie'])
            ->orderBy('session_date')
            ->orderBy('start_time')
            ->get();

        return view('admin.sessions.index', compact('sessions'));
    }


    public function create()
    {
        $halls = CinemaHall::all();
        $movies = Movie::all();

        return view('admin.sessions.create', compact('halls', 'movies'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'hall_id'       => 'required|exists:cinema_halls,id',
            'movie_id'      => 'required|exists:movies,id',
            'start_time'    => 'required|date_format:H:i',
            'price_regular' => 'nullable',
            'price_vip'     => 'nullable',
        ]);

        $hall = CinemaHall::findOrFail($request->hall_id);
        if (!$hall->is_active) {
            return back()->withErrors("Этот зал закрыт, нельзя добавить сеанс.");
        }

        $movie = Movie::findOrFail($request->movie_id);

        $priceRegular = $request->price_regular !== null && $request->price_regular !== ''
            ? (int)$request->price_regular
            : ($hall->price->regular_price ?? 0);

        $priceVip = $request->price_vip !== null && $request->price_vip !== ''
            ? (int)$request->price_vip
            : ($hall->price->vip_price ?? 0);

        $daysToGenerate = 7;
        $today = Carbon::today();

        for ($i = 0; $i < $daysToGenerate; $i++) {

            $sessionDate = (clone $today)->addDays($i)->toDateString();

            $start = Carbon::parse("$sessionDate {$request->start_time}:00");
            $end   = (clone $start)->addMinutes($movie->duration);

        
            $existing = MovieSession::where('hall_id', $hall->id)
                ->where('session_date', $sessionDate)
                ->get();

            foreach ($existing as $sess) {
                if (
                    ($start < Carbon::parse($sess->end_time)) &&
                    ($end > Carbon::parse($sess->start_time))
                ) {
                    return redirect()
                        ->back()
                        ->with('error', "Новый сеанс пересекается с уже существующим.");
                }
            }

            MovieSession::create([
                'hall_id'       => $request->hall_id,
                'movie_id'      => $request->movie_id,
                'session_date'  => $sessionDate,
                'start_time'    => $start->format('Y-m-d H:i:s'),
                'end_time'      => $end->format('Y-m-d H:i:s'),
                'price_regular' => $priceRegular,
                'price_vip'     => $priceVip,
            ]);
        }

        return redirect()->route('admin.dashboard')
            ->with('success', "Сеансы созданы на {$daysToGenerate} дней.");
    }


    public function edit(MovieSession $session)
    {
        $halls = CinemaHall::all();
        $movies = Movie::all();

        return view('admin.sessions.edit', compact('session', 'halls', 'movies'));
    }


    public function update(Request $request, MovieSession $session)
    {
        $request->validate([
            'hall_id'       => 'required|exists:cinema_halls,id',
            'movie_id'      => 'required|exists:movies,id',
            'start_time'    => 'required|date_format:H:i',
            'session_date'  => 'required|date',
            'price_regular' => 'nullable',
            'price_vip'     => 'nullable',
        ]);

        $hall = CinemaHall::findOrFail($request->hall_id);
        if (!$hall->is_active) {
            return back()->withErrors("Этот зал закрыт, нельзя изменить сеанс.");
        }

        $movie = Movie::findOrFail($request->movie_id);

        $start = Carbon::parse($request->session_date . ' ' . $request->start_time . ':00');
        $end   = (clone $start)->addMinutes($movie->duration);

        
        $existing = MovieSession::where('hall_id', $hall->id)
            ->where('session_date', $request->session_date)
            ->where('id', '!=', $session->id)
            ->get();

        foreach ($existing as $sess) {
            if (
                ($start < Carbon::parse($sess->end_time)) &&
                ($end > Carbon::parse($sess->start_time))
            ) {
                return redirect()
                    ->back()
                    ->with('error', "Изменённый сеанс пересекается с другим сеансом.");
            }
        }

        $priceRegular = $request->price_regular !== null && $request->price_regular !== ''
            ? (int)$request->price_regular
            : $session->price_regular;

        $priceVip = $request->price_vip !== null && $request->price_vip !== ''
            ? (int)$request->price_vip
            : $session->price_vip;

        $session->update([
            'hall_id'       => $request->hall_id,
            'movie_id'      => $request->movie_id,
            'session_date'  => $request->session_date,
            'start_time'    => $start,
            'end_time'      => $end,
            'price_regular' => $priceRegular,
            'price_vip'     => $priceVip,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Сеанс обновлён');
    }


   public function destroy(MovieSession $session)
    {
        MovieSession::where('movie_id', $session->movie_id)
            ->where('hall_id', $session->hall_id)
            ->whereTime('start_time', \Carbon\Carbon::parse($session->start_time)->format('H:i:s'))
            ->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Сеансы удалены');
    }
}
