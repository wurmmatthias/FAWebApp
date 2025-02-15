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

Route::get('/items', function () {
    return view('dashboard.items');
})->middleware(['auth'])->name('items');

Route::get('/downloads', function () {
    return view('dashboard.downloads');
})->middleware(['auth'])->name('downloads');

Route::get('/dashboard', function () {
    $user = Auth::user();
    // Fetch all saved transactions from the database
    $transactions = SaveData::orderBy('created_at', 'desc')->get();

    // Only sum deposits (where transactionType = 1)
    $totalGold = SaveData::where('transactionType', 1)->sum('AmountGold') ?? 0;

    // Debugging: Log the correct totalGold
    logger('Filtered Total Gold:', ['totalGold' => $totalGold]);

    return view('dashboard', ['transactions' => $transactions, 'totalGold' => $totalGold], compact('user'));
})->middleware(['auth'])->name('dashboard');

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
