<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MovieSession;
use App\Models\CinemaHall;
use App\Models\Movie;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $halls  = CinemaHall::all();
        $movies = Movie::all();

        return view('admin.sessions.create', compact('halls', 'movies'));
    }

    public function store(Request $request)
{
    $rules = [
        'hall_id'    => 'required|exists:cinema_halls,id',
        'movie_id'   => 'required|exists:movies,id',
        'start_time' => 'required|date_format:H:i',
    ];

   
    if ($request->expectsJson()) {
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
    } else {
        $request->validate($rules);
    }

   
    $hall = CinemaHall::findOrFail($request->hall_id);

    if (!$hall->is_active) {
        return $request->expectsJson()
            ? response()->json(['message' => 'Зал закрыт'], 422)
            : back()->withErrors(['hall_id' => 'Зал закрыт']);
    }

    $movie = Movie::findOrFail($request->movie_id);

    
    $start = now()->addDay()->setTimeFromTimeString($request->start_time);
    $end   = (clone $start)->addMinutes($movie->duration);

   
    $existingSessions = MovieSession::where('hall_id', $hall->id)
        ->where('session_date', $start->toDateString())
        ->get();

    foreach ($existingSessions as $sess) {
        if (
            $start < Carbon::parse($sess->end_time) &&
            $end > Carbon::parse($sess->start_time)
        ) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Сеанс пересекается'], 422)
                : back()->withErrors(['start_time' => 'Сеанс пересекается']);
        }
    }


    MovieSession::create([
        'hall_id'      => $hall->id,
        'movie_id'     => $movie->id,
        'session_date' => $start->toDateString(),
        'start_time'   => $start,
        'end_time'     => $end,
    ]);

   
    return $request->expectsJson()
        ? response()->json(['success' => true], 201)
        : redirect()->route('admin.dashboard');
}


    public function edit(MovieSession $session)
    {
        $halls  = CinemaHall::all();
        $movies = Movie::all();

        return view('admin.sessions.edit', compact('session', 'halls', 'movies'));
    }

    public function update(Request $request, MovieSession $session)
    {
        $request->validate([
            'hall_id'      => 'required|exists:cinema_halls,id',
            'movie_id'     => 'required|exists:movies,id',
            'start_time'   => 'required|date_format:H:i',
            'session_date' => 'required|date',
        ]);

        $hall = CinemaHall::findOrFail($request->hall_id);

        if (!$hall->is_active) {
            return back()->with('error', 'Зал закрыт. Нельзя изменить сеанс.');
        }

        $movie = Movie::findOrFail($request->movie_id);

        $start = Carbon::parse($request->session_date . ' ' . $request->start_time);
        $end   = (clone $start)->addMinutes($movie->duration);

        $existing = MovieSession::where('hall_id', $hall->id)
            ->where('session_date', $request->session_date)
            ->where('id', '!=', $session->id)
            ->get();

        foreach ($existing as $sess) {
            if (!$sess->end_time) {
                continue;
            }

            if (
                $start < Carbon::parse($sess->end_time) &&
                $end > Carbon::parse($sess->start_time)
            ) {
                return back()->with('error', 'Сеанс пересекается с другим.');
            }
        }

        $session->update([
            'hall_id'      => $hall->id,
            'movie_id'     => $movie->id,
            'session_date' => $request->session_date,
            'start_time'   => $start,
            'end_time'     => $end,
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Сеанс обновлён');
    }

  
    public function destroy(MovieSession $session)
    {
        MovieSession::where('movie_id', $session->movie_id)
            ->where('hall_id', $session->hall_id)
            ->whereTime(
                'start_time',
                Carbon::parse($session->start_time)->format('H:i:s')
            )
            ->delete();

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Сеансы удалены');
    }
}
