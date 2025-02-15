<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

use App\Services\GuildService;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        $user = $request->user();
        $characters = $user->getBattleNetCharacters();

        return view('profile.edit', [
            'user' => $user,
            'characters' => $characters, // Ensure this is passed
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request, GuildService $guildService)
    {
        $user = Auth::user();

        $request->validate([
            'main_character' => 'nullable|string',
        ]);

        if ($request->filled('main_character')) {
            $character = json_decode($request->main_character, true);
            $characterName = strtolower($character['name']);
            $realmSlug = $character['realm']['slug'];

            // Fetch correct character media URL
            $character['avatar'] = $guildService->getCharacterMedia($realmSlug, $characterName);

            // Store in database
            $user->main_character = json_encode($character);
            $user->save();
        }

        return back()->with('status', 'profile-updated');
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
