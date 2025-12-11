<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HallPrice;
use App\Models\CinemaHall;
use Illuminate\Http\Request;

class HallPriceController extends Controller
{
    public function update(Request $request, CinemaHall $hall)
{
    $request->validate([
        'regular_price' => 'required|integer|min:1',
        'vip_price'     => 'required|integer|min:1',
    ]);

    $price = HallPrice::updateOrCreate(
        ['hall_id' => $hall->id],
        [
            'regular_price' => $request->regular_price,
            'vip_price'     => $request->vip_price,
        ]
    );

    if ($request->ajax() || $request->expectsJson()) {
        return response()->json([
            'success' => true,
            'price'   => $price,
        ]);
    }

    return redirect()
        ->route('admin.dashboard')
        ->with('success', 'Цены обновлены');
}

}
