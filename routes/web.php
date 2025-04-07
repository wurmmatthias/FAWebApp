<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Transaction;
use App\Http\Controllers\GuildController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\LootCouncilController;




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


Route::get('/items', function () {
    return view('dashboard.items');
})->middleware(['auth'])->name('items');

Route::get('/downloads', function () {
    return view('dashboard.downloads');
})->middleware(['auth'])->name('downloads');

Route::get('/impressum', function () {
    return view('dashboard.impressum');
})->middleware(['auth'])->name('impressum');

Route::get('/datenschutz', function () {
    return view('dashboard.privacypolicy');
})->middleware(['auth'])->name('datenschutz');

Route::get('/gilde', [GuildController::class, 'show'])->name('gilde');

Route::get('/lootcouncil', [LootCouncilController::class, 'index'])->name('lootcouncil.index')->middleware(['auth']);
Route::post('/lootcouncil/upload', [LootCouncilController::class, 'upload'])->name('lootcouncil.upload');



Route::get('/dashboard', function () {
    $user = Auth::user();
    $mainCharacter = json_decode($user->main_character, true) ?? null;

    // Create "Character - Realm" format
    $characterFullName = null;
    if ($mainCharacter && isset($mainCharacter['name'], $mainCharacter['realm']['slug'])) {
        $characterFullName = "{$mainCharacter['name']} - {$mainCharacter['realm']['slug']}";
    }

    // Fetch transactions using full character name
    $transactions = Transaction::when($characterFullName, function ($query, $characterFullName) {
        return $query->where('player_name', $characterFullName);
    })->orderBy('transaction_timestamp', 'desc')->get();

    // Fetch all transactions for history
    $allTransactions = Transaction::orderBy('transaction_timestamp', 'desc')->get();

    return view('dashboard', [
        'user' => $user,
        'mainCharacter' => $mainCharacter,
        'characterFullName' => $characterFullName,
        'transactions' => $transactions,
        'allTransactions' => $allTransactions,
    ]);
})->middleware(['auth'])->name('dashboard');


Route::get('/dashboard', function () {
    $user = Auth::user();

    $mainCharacter = json_decode($user->main_character, true) ?? null;

    // Create "Character - Realm" name
    $characterFullName = null;
    if ($mainCharacter && isset($mainCharacter['name'], $mainCharacter['realm']['name'])) {
        $characterFullName = "{$mainCharacter['name']} - {$mainCharacter['realm']['name']}";
    }

    // Fetch transactions for the main character OR an empty collection if no character is set
    $transactions = Transaction::when($characterFullName, function ($query, $characterFullName) {
        return $query->where('player_name', $characterFullName);
    })->orderBy('transaction_timestamp', 'desc')->get();

    // Fetch all transactions for history
    $allTransactions = Transaction::orderBy('transaction_timestamp', 'desc')->get();

    return view('dashboard', [
        'user' => $user,
        'mainCharacter' => $mainCharacter,
        'characterFullName' => $characterFullName,
        'transactions' => $transactions,
        'allTransactions' => $allTransactions,
    ]);
})->middleware(['auth'])->name('dashboard');


Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', function () {
        // Inline check for admin privileges
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Access denied');
        }

        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/admin/users', function () {
        // Inline check for admin privileges
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Access denied');
        }

        $users = User::orderBy('name')->get();

        return view('admin.users', compact('users'));
    })->name('admin.users');

    Route::get('/admin/settings', function () {
        // Inline check for admin privileges
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Access denied');
        }

        return view('admin.settings');
    })->name('admin.settings');

    Route::get('/admin/transactions', function () {
        // Inline check for admin privileges
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Access denied');
        }

        return view('admin.transactions');
    })->name('admin.transactions');

    // ✅ Correctly name the promote and revoke routes
    Route::post('/admin/users/{user}/promote', [UserController::class, 'promote'])->name('admin.users.promote');
    Route::post('/admin/users/{user}/revoke', [UserController::class, 'revoke'])->name('admin.users.revoke');
    Route::post('/admin/transactions/store', [TransactionController::class, 'storeManually'])->name('admin.transactions.store');

});




Route::get('/auth/battlenet/redirect', function () {
    return Socialite::driver('battlenet')->setScopes([
        'openid', 'wow.profile'
    ])->redirect();
});

Route::get('/auth/battlenet/callback', function () {
    try {
        $battlenetUser = Socialite::driver('battlenet')->user();
        $accessToken = $battlenetUser->token;

        if (!$accessToken) {
            throw new Exception("Battle.net token is missing!");
        }

        // ✅ Log the access token to debug
        logger('Battle.net OAuth Token:', ['token' => $accessToken]);

        // 🔹 Step 1: Get Battle.net User Info
        $userInfoResponse = Http::withHeaders([
            'Authorization' => "Bearer {$accessToken}"
        ])->get("https://oauth.battle.net/userinfo");

        $userInfo = $userInfoResponse->json();
        $battleTag = $userInfo['battletag'] ?? 'Unknown';

        // 🔹 Step 2: Fetch WoW Accounts and Characters
        $wowProfileResponse = Http::withHeaders([
            'Authorization' => "Bearer {$accessToken}"
        ])->get("https://eu.api.blizzard.com/profile/user/wow?namespace=profile-eu&locale=en_GB");

        $wowProfile = $wowProfileResponse->json();

        // Default Avatar (if no WoW character found)
        $avatarUrl = "https://ui-avatars.com/api/?name=" . urlencode($battleTag) . "&size=100";

        if (!empty($wowProfile['wow_accounts'][0]['characters'])) {
            $character = $wowProfile['wow_accounts'][0]['characters'][0]; // Use the first character
            $realmSlug = $character['realm']['slug'];
            $characterName = strtolower($character['name']);

            // 🔹 Step 3: Fetch Character Profile to Get Avatar
            $characterProfileResponse = Http::withHeaders([
                'Authorization' => "Bearer {$accessToken}"
            ])->get("https://eu.api.blizzard.com/profile/wow/character/{$realmSlug}/{$characterName}?namespace=profile-eu&locale=en_GB");

            $characterProfile = $characterProfileResponse->json();

            if (!empty($characterProfile['assets'])) {
                foreach ($characterProfile['assets'] as $asset) {
                    if ($asset['key'] === 'avatar') {
                        $avatarUrl = $asset['value']; // Set avatar to WoW character image
                        break;
                    }
                }
            }
        }

        // 🔹 Step 4: Store User Data in Database
        $user = \App\Models\User::updateOrCreate(
            ['battlenet_id' => $battlenetUser->id], // Match on Battle.net ID
            [
                'name' => $battleTag,
                'email' => $battlenetUser->email ?? null,
                'avatar' => $avatarUrl, // Save WoW avatar
                'password' => bcrypt(str()->random(16)), // Random password for security
            ]
        );

        // 🔹 Step 5: Force Updating Token Separately
        $user->battlenet_token = $accessToken;
        $user->save(); // ✅ This ensures the token is saved!

        logger('User saved:', ['user' => $user]);

        // 🔹 Step 6: Log the User In
        Auth::login($user);

        return redirect('/dashboard');

    } catch (\Exception $e) {
        logger('Battle.net Authentication Error:', ['error' => $e->getMessage()]);
        return redirect('/')->with('error', 'Failed to authenticate with Battle.net.');
    }
});




require __DIR__.'/auth.php';
