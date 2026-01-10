<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MovieSession;
use App\Models\CinemaHall;
use App\Models\Movie;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MovieSessionController extends Controller
{
   
    public function store(Request $request)
    {
        $request->validate([
            'hall_id'    => 'required|exists:cinema_halls,id',
            'movie_id'   => 'required|exists:movies,id',
            'start_time' => 'required|date_format:H:i',
        ]);

        $hall = CinemaHall::findOrFail($request->hall_id);
        if (!$hall->is_active) {
            return response()->json([
                'message' => 'Зал закрыт. Нельзя добавлять сеанс'
            ], 422);
        }

        $movie = Movie::findOrFail($request->movie_id);

        $start = Carbon::createFromFormat('H:i', $request->start_time);
        $end   = (clone $start)->addMinutes($movie->duration);

      
        $sessions = MovieSession::where('hall_id', $hall->id)->get();

        foreach ($sessions as $session) {
            $sStart = Carbon::createFromFormat('H:i:s', $session->start_time);
            $sEnd   = Carbon::createFromFormat('H:i:s', $session->end_time);

            if ($start < $sEnd && $end > $sStart) {
            return response()->json([
                'message' => 'Сеанс пересекается с другим в этом зале'
            ], 422);
        }
        }

        MovieSession::create([
            'hall_id'       => $hall->id,
            'movie_id'      => $movie->id,
            'start_time'    => $start->format('H:i:s'),
            'end_time'      => $end->format('H:i:s'),
            'price_regular' => 0,
            'price_vip'     => 0,
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Сеанс успешно создан');
    }

   
    public function update(Request $request, MovieSession $session)
    {
        $request->validate([
            'hall_id'    => 'required|exists:cinema_halls,id',
            'movie_id'   => 'required|exists:movies,id',
            'start_time' => 'required|date_format:H:i',
        ]);

        $hall = CinemaHall::findOrFail($request->hall_id);
        if (!$hall->is_active) {
            return back()->withErrors(['hall_id' => 'Зал закрыт']);
        }

        $movie = Movie::findOrFail($request->movie_id);

        $start = Carbon::createFromFormat('H:i', $request->start_time);
        $end   = (clone $start)->addMinutes($movie->duration);

        $sessions = MovieSession::where('hall_id', $hall->id)
            ->where('id', '!=', $session->id)
            ->get();

        foreach ($sessions as $s) {
            $sStart = Carbon::createFromFormat('H:i:s', $s->start_time);
            $sEnd   = Carbon::createFromFormat('H:i:s', $s->end_time);

            if ($start < $sEnd && $end > $sStart) {
                return back()->withErrors([
                    'start_time' => 'Сеанс пересекается с другим',
                ]);
            }
        }

        $session->update([
            'hall_id'    => $hall->id,
            'movie_id'   => $movie->id,
            'start_time' => $start->format('H:i:s'),
            'end_time'   => $end->format('H:i:s'),
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Сеанс обновлён');
    }

   
    public function destroy(MovieSession $session)
    {
        $session->delete();

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Сеанс удалён');
    }
}
