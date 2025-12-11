<?php 

namespace App\Http\Controllers\Admin;

use App\Models\Seat;
use App\Http\Controllers\Controller;
use App\Models\CinemaHall;
use Illuminate\Http\Request;

class CinemaHallController extends Controller
{
    public function index()
    {
        $halls = CinemaHall::all();
        return view('admin.halls.index', compact('halls'));
    }

    public function create()
    {
        return view('admin.halls.create');
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
    ]);

    
    $rows = 5;
    $seatsPerRow = 5;

    
    $hall = CinemaHall::create([
        'name'          => $validated['name'],
        'rows'          => $rows,
        'seats_per_row' => $seatsPerRow,
    ]);

    
    for ($r = 1; $r <= $rows; $r++) {
        for ($s = 1; $s <= $seatsPerRow; $s++) {
            Seat::create([
                'hall_id'     => $hall->id,
                'row_number'  => $r,
                'seat_number' => $s,
                'seat_type'   => 'regular', 
            ]);
        }
    }

 
    return redirect()
        ->route('admin.dashboard')
        ->with('success', 'Зал создан!');
}

public function update(Request $request, CinemaHall $hall)
{
    $data = $request->validate([
        'name'          => 'required|string|max:255',
        'rows'          => 'required|integer|min:1',
        'seats_per_row' => 'required|integer|min:1',
        'seats_changes' => 'nullable|string',
    ]);

    $oldRows        = $hall->rows;
    $oldSeatsPerRow = $hall->seats_per_row;

    $hall->update([
        'name'          => $data['name'],
        'rows'          => $data['rows'],
        'seats_per_row' => $data['seats_per_row'],
    ]);


    if ($oldRows != $hall->rows || $oldSeatsPerRow != $hall->seats_per_row) {

        $oldSeats = $hall->seats()
            ->orderBy('row_number')
            ->orderBy('seat_number')
            ->get()
            ->groupBy('row_number')
            ->map(fn($row) => $row->keyBy('seat_number'));

        $hall->seats()->delete();

        for ($r = 1; $r <= $hall->rows; $r++) {
            for ($s = 1; $s <= $hall->seats_per_row; $s++) {
                $oldType = $oldSeats[$r][$s]->seat_type ?? 'regular';

                Seat::create([
                    'hall_id'     => $hall->id,
                    'row_number'  => $r,
                    'seat_number' => $s,
                    'seat_type'   => $oldType,
                ]);
            }
        }
    }

   
    if (!empty($data['seats_changes'])) {
        $changes = json_decode($data['seats_changes'], true) ?? [];

        foreach ($changes as $seatId => $type) {
            if (!in_array($type, ['regular','vip','disabled'], true)) {
                continue;
            }

            Seat::where('id', $seatId)
                ->where('hall_id', $hall->id)
                ->update(['seat_type' => $type]);
        }
    }

  
    $seats = $hall->seats()
        ->orderBy('row_number')
        ->orderBy('seat_number')
        ->get();

   
    if ($request->expectsJson() || $request->ajax()) {
        return response()->json([
            'seats' => $seats,
        ]);
    }

   
    return redirect()
        ->route('admin.dashboard')
        ->with('success', 'Настройки зала обновлены.');
}

   public function toggle(CinemaHall $hall)
      {
            if (request()->has('status')) {
                $hall->is_active = (bool) request('status');
            } else {
                $hall->is_active = !$hall->is_active;
            }

            $hall->save();

            if (request()->ajax()) {
                return response()->json([
                    'success'    => true,
                    'new_status' => $hall->is_active,
                ]);
            }

            return back()->with('success', 'Статус обновлён');
        }  


   
   


    public function seats(CinemaHall $hall)
    {
        $seats = $hall->seats()
            ->orderBy('row_number')
            ->orderBy('seat_number')
            ->get();

        return response()->json([
            'rows' => $hall->rows,
            'seats_per_row' => $hall->seats_per_row,
            'seats' => $seats
        ]);
    }


    public function toggleSeat(CinemaHall $hall, Seat $seat)
    {
        if ($seat->hall_id !== $hall->id) {
            return back()->with('error', 'Неверное место');
        }

        $types = ['regular', 'vip', 'disabled'];

        $currentIndex = array_search($seat->seat_type, $types);

        if ($currentIndex === false) {
            $currentIndex = 0;
        }

        $nextIndex = ($currentIndex + 1) % count($types);

        $seat->seat_type = $types[$nextIndex];
        $seat->save();

        return back()->with('success', 'Тип места изменён');
    }

    public function delete(Request $request)
    {
        $request->validate([
            'hall_id' => 'required|integer|exists:cinema_halls,id'
        ]);

        $hall = CinemaHall::find($request->hall_id);

        $hall->seats()->delete();
        $hall->sessions()->delete();

        $hall->delete();

        return redirect()->back()->with('success', 'Зал удалён!');
    }

    public function config(CinemaHall $hall)
    {
        $seats = $hall->seats()
            ->orderBy('row_number')
            ->orderBy('seat_number')
            ->get();

        return view('admin.halls.config', compact('hall', 'seats'));
    }

    public function ajaxToggleSeat(CinemaHall $hall, Seat $seat)
    {
        if ($seat->hall_id !== $hall->id) {
            return response()->json(['error' => 'Invalid seat'], 403);
        }

        $types = ['regular', 'vip', 'disabled'];

        $current = array_search($seat->seat_type, $types);
        $next = ($current + 1) % count($types);

        $seat->seat_type = $types[$next];
        $seat->save();

        return response()->json([
            'success' => true,
            'new_type' => $seat->seat_type
        ]);
    }
}
