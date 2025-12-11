<?php  

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CinemaHallController;
use App\Http\Controllers\Admin\MovieController;
use App\Http\Controllers\Admin\MovieSessionController;
use App\Http\Controllers\Admin\HallPriceController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\HallController;
use App\Http\Controllers\Client\PaymentController;
use App\Http\Controllers\Client\TicketController;

Route::get('/create-admin', function () {

    \App\Models\User::where('email', 'admin@example.com')->delete();

    $user = \App\Models\User::create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => bcrypt('password123'),
        'is_admin' => true,
        'role' => 'admin',
    ]);

    return [
        'created' => true,
        'user' => $user,
        'login_url' => url('/login'),
        'credentials' => [
            'email' => 'admin@example.com',
            'password' => 'password123'
        ]
    ];
});
Route::get('/', [HomeController::class, 'index'])->name('client.home');
Route::get('/hall/{session}', [HallController::class, 'index'])
    ->name('client.hall');

Route::get('/payment', [PaymentController::class, 'index'])
    ->name('client.payment');

Route::post('/payment', [PaymentController::class, 'store'])
    ->name('client.payment.store');
Route::get('/ticket/{order}', [TicketController::class, 'show'])
    ->name('client.ticket');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});




Route::middleware(['auth', 'isAdmin'])->prefix('admin')->group(function () {

   
    Route::get('/', [AdminDashboardController::class, 'index'])
    ->name('admin.dashboard');


    Route::get('/halls', [CinemaHallController::class, 'index'])->name('admin.halls.index');
    Route::get('/halls/create', [CinemaHallController::class, 'create'])->name('admin.halls.create');
    Route::post('/halls', [CinemaHallController::class, 'store'])->name('admin.halls.store');
    Route::get('/halls/{hall}/edit', [CinemaHallController::class, 'edit'])->name('admin.halls.edit');
    Route::put('/halls/{hall}', [CinemaHallController::class, 'update'])->name('admin.halls.update');
    Route::post('/halls/delete', [CinemaHallController::class, 'delete'])->name('admin.halls.delete');

  
    Route::post('/halls/{hall}/toggle', [CinemaHallController::class, 'toggle'])
        ->name('admin.halls.toggle');

  
    Route::post(
        '/halls/{hall}/seats/{seat}/toggle-ajax',
        [CinemaHallController::class, 'ajaxToggleSeat']
    )->name('admin.halls.seat.toggle.ajax');

   
    Route::post('/halls/{hall}/prices', [HallPriceController::class, 'update'])
        ->name('admin.halls.prices.update');

  
    Route::get('/movies', [MovieController::class, 'index'])->name('admin.movies.index');
    Route::get('/movies/create', [MovieController::class, 'create'])->name('admin.movies.create');
    Route::post('/movies', [MovieController::class, 'store'])->name('admin.movies.store');
    Route::get('/movies/{movie}/edit', [MovieController::class, 'edit'])->name('admin.movies.edit');
    Route::put('/movies/{movie}', [MovieController::class, 'update'])->name('admin.movies.update');
    Route::delete('/movies/{movie}', [MovieController::class, 'destroy'])->name('admin.movies.destroy');

   
    Route::get('/sessions', [MovieSessionController::class, 'index'])->name('admin.sessions.index');
    Route::get('/sessions/create', [MovieSessionController::class, 'create'])->name('admin.sessions.create');
    Route::post('/sessions', [MovieSessionController::class, 'store'])->name('admin.sessions.store');
    Route::get('/sessions/{session}/edit', [MovieSessionController::class, 'edit'])->name('admin.sessions.edit');
    Route::put('/sessions/{session}', [MovieSessionController::class, 'update'])->name('admin.sessions.update');
    Route::delete('/sessions/{session}', [MovieSessionController::class, 'destroy'])->name('admin.sessions.destroy');
    Route::post('/open-sales', [AdminDashboardController::class, 'openSales'])
    ->name('admin.sales.open');



    Route::get('/debug/fill-seats-7', function () {

        $hall = \App\Models\CinemaHall::find(7);
        if (!$hall) {
            return 'Hall 7 not found';
        }

        \App\Models\Seat::where('hall_id', $hall->id)->delete();

        for ($r = 1; $r <= 5; $r++) {
            for ($s = 1; $s <= 8; $s++) {
                \App\Models\Seat::create([
                    'hall_id'     => $hall->id,
                    'row_number'  => $r,
                    'seat_number' => $s,
                    'seat_type'   => 'regular',
                ]);
            }
        }

        return 'Seats for hall 7 created!';
    })->name('admin.debug.fillSeats7');
   
});



Route::get('/debug-login', function () {
    $user = \App\Models\User::where('email', 'admin@example.com')->first();

    if (!$user) return 'User not found';

    return [
        'email_exists' => true,
        'password_valid' => \Illuminate\Support\Facades\Hash::check('password', $user->password),
        'is_admin' => $user->is_admin,
        'role' => $user->role,
    ];
});
Route::get('/force-logout', function () {
    auth()->logout();
    session()->invalidate();
    session()->regenerateToken();
    return 'Logged out';
});
use App\Models\User;
use Illuminate\Support\Facades\Hash;

Route::get('/fix-admin', function () {
    $user = User::updateOrCreate(
        ['email' => 'admin@example.com'],
        [
            'name'      => 'Admin User',
            'password'  => Hash::make('password123'),
            'is_admin'  => 1,
            'role'      => 'admin',
        ]
    );

    return [
        'id'        => $user->id,
        'email'     => $user->email,
        'is_admin'  => $user->is_admin,
        'role'      => $user->role,
        'password'  => $user->password,
    ];
});

require __DIR__.'/auth.php';
