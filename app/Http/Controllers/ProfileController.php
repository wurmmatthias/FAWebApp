<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;


use App\Services\GuildService;


class ProfileController extends Controller
{

    public function getCharacterMedia($realmSlug, $characterName)
    {
        $accessToken = Auth::user()->battlenet_token; // Ensure user is authenticated via Battle.net

        $url = "https://eu.api.blizzard.com/profile/wow/character/{$realmSlug}/{$characterName}/character-media?namespace=profile-eu&locale=de_DE";

        $response = Http::withToken($accessToken)->get($url);

        $data = $response->json();

        if (!isset($data['assets'])) {
            \Log::error("Failed to fetch character media: " . json_encode($data));
            return asset('images/default-avatar.jpg'); // Return fallback avatar
        }

        // Extract avatar URL
        foreach ($data['assets'] as $asset) {
            if ($asset['key'] === 'avatar') {
                return $asset['value'];
            }
        }

        return asset('images/default-avatar.jpg'); // Fallback in case no avatar is found
    }

    public function edit()
    {
        $user = Auth::user();

        if (!$user->battlenet_token) {
            return view('profile.edit', compact('user'))->with('error', 'Kein Battle.net Token vorhanden.');
        }

        $accessToken = $user->battlenet_token;
        $guildName = strtolower(env('GUILD_NAME'));
        $realmSlug = strtolower(env('REALM_NAME'));

        // Fetch user's WoW characters
        $wowProfileResponse = \Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => "Bearer {$accessToken}",
        ])->get("https://eu.api.blizzard.com/profile/user/wow?namespace=profile-eu&locale=de_DE");

        $wowProfile = $wowProfileResponse->json();
        $userCharacters = $wowProfile['wow_accounts'][0]['characters'] ?? [];

        // Fetch the guild roster
        $guildRosterResponse = \Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => "Bearer {$accessToken}",
        ])->get("https://eu.api.blizzard.com/data/wow/guild/{$realmSlug}/{$guildName}/roster?namespace=profile-eu&locale=de_DE");

        $guildRoster = $guildRosterResponse->json();
        $guildMembers = collect($guildRoster['members'] ?? [])->mapWithKeys(function ($member) {
            return [strtolower($member['character']['name']) => true];
        });

        // Filter characters that exist in the guild
        $characters = collect($userCharacters)->filter(function ($character) use ($guildMembers) {
            return isset($guildMembers[strtolower($character['name'])]);
        })->map(function ($character) {
            return [
                'name' => $character['name'],
                'level' => $character['level'],
                'playable_class' => [
                    'id' => $character['playable_class']['id'],
                ],
                'realm' => [
                    'slug' => $character['realm']['slug'],
                ],
                'avatar' => "https://render.worldofwarcraft.com/eu/character/{$character['realm']['slug']}/" . strtolower($character['name']) . ".jpg"
            ];
        })->values()->all();

        return view('profile.edit', compact('user', 'characters'));
    }

    // ✅ Add this update function to save the main character
    public function update(Request $request)
    {
        $user = Auth::user();

        if (!$user->battlenet_id) {
            return redirect()->route('profile.edit')->with('error', 'Nur Battle.net Benutzer können einen Hauptcharakter setzen.');
        }

        $request->validate([
            'main_character' => 'required|json',
        ]);

        $mainCharacter = json_decode($request->main_character, true);
        $realmSlug = strtolower($mainCharacter['realm']['slug']);
        $characterName = strtolower($mainCharacter['name']);

        // Fetch Character Media
        $characterMediaUrl = $this->getCharacterMedia($realmSlug, $characterName);

        // Save to database
        $mainCharacter['avatar'] = $characterMediaUrl;
        $user->main_character = json_encode($mainCharacter);
        $user->save();

        return redirect()->route('profile.edit')->with('status', 'Dein Hauptcharakter wurde erfolgreich gespeichert! ✅');
    }
}
