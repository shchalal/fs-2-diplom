<?php  

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MovieSession;
use App\Models\CinemaHall;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieSessionController extends Controller
{
    public function index()
    {
        $sessions = MovieSession::with(['hall', 'movie'])
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
            'hall_id'     => 'required|exists:cinema_halls,id',
            'movie_id'    => 'required|exists:movies,id',
            'start_time'  => 'required|date_format:H:i',
            'price_regular' => 'nullable|integer|min:0',
            'price_vip'     => 'nullable|integer|min:0',
        ]);

   
        $hall = CinemaHall::findOrFail($request->hall_id);
        if (!$hall->is_active) {
            return back()->withErrors("Этот зал закрыт, нельзя добавить сеанс.");
        }


        $movie = Movie::findOrFail($request->movie_id);

   
        $start = now()->format('Y-m-d') . ' ' . $request->start_time . ':00';

      
        $end = date('Y-m-d H:i:s', strtotime($start . " +{$movie->duration} minutes"));

       
        $priceRegular = $request->price_regular ?? ($hall->price->regular_price ?? 0);
        $priceVip     = $request->price_vip     ?? ($hall->price->vip_price ?? 0);

        MovieSession::create([
            'hall_id'       => $request->hall_id,
            'movie_id'      => $request->movie_id,
            'start_time'    => $start,
            'end_time'      => $end,
            'price_regular' => $priceRegular,
            'price_vip'     => $priceVip,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Сеанс создан');
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
            'hall_id'     => 'required|exists:cinema_halls,id',
            'movie_id'    => 'required|exists:movies,id',
            'start_time'  => 'required|date_format:H:i',
            'price_regular' => 'nullable|integer|min:0',
            'price_vip'     => 'nullable|integer|min:0',
        ]);

       
        $hall = CinemaHall::findOrFail($request->hall_id);
        if (!$hall->is_active) {
            return back()->withErrors("Этот зал закрыт, нельзя изменить сеанс.");
        }

      
        $movie = Movie::findOrFail($request->movie_id);

      
        $start = now()->format('Y-m-d') . ' ' . $request->start_time . ':00';
        $end   = date('Y-m-d H:i:s', strtotime($start . " +{$movie->duration} minutes"));

       
        $priceRegular = $request->price_regular ?? ($hall->price->regular_price ?? $session->price_regular);
        $priceVip     = $request->price_vip     ?? ($hall->price->vip_price     ?? $session->price_vip);

        $session->update([
            'hall_id'       => $request->hall_id,
            'movie_id'      => $request->movie_id,
            'start_time'    => $start,
            'end_time'      => $end,
            'price_regular' => $priceRegular,
            'price_vip'     => $priceVip,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Сеанс обновлён');
    }

    public function destroy(MovieSession $session)
    {
        $session->delete();
        return back()->with('success', 'Сеанс удалён');
    }
}
