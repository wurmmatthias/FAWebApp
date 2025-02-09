<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\SaveData;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', function () {
    // Fetch all saved transactions from the database
    $transactions = SaveData::orderBy('created_at', 'desc')->get();

    // Only sum deposits (where transactionType = 1)
    $totalGold = SaveData::where('transactionType', 1)->sum('AmountGold') ?? 0;

    // Debugging: Log the correct totalGold
    logger('Filtered Total Gold:', ['totalGold' => $totalGold]);

    return view('dashboard', ['transactions' => $transactions, 'totalGold' => $totalGold]);
})->middleware(['auth'])->name('dashboard');

Route::get('/auth/battlenet/redirect', function () {
    return Socialite::driver('battlenet')->redirect();
});

Route::get('/auth/battlenet/callback', function () {
    $battlenetUser = Socialite::driver('battlenet')->user();

    $user = User::updateOrCreate(
        ['battlenet_id' => $battlenetUser->id],
        [
            'name' => $battlenetUser->nickname ?? 'Unknown User',
            'email' => $battlenetUser->email ?? null,
            'avatar' => $battlenetUser->avatar ?? null,
            'password' => bcrypt(str()->random(16)), // Generate a random password
        ]
    );

    Auth::login($user);

    return redirect('/dashboard');
});



require __DIR__.'/auth.php';
