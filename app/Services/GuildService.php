<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class GuildService
{

    private $classes = [
        1 => 'Warrior', 2 => 'Paladin', 3 => 'Hunter', 4 => 'Rogue',
        5 => 'Priest', 6 => 'Death Knight', 7 => 'Shaman', 8 => 'Mage',
        9 => 'Warlock', 10 => 'Monk', 11 => 'Druid', 12 => 'Demon Hunter',
        13 => 'Evoker'
    ];

    private $races = [
        1 => 'Mensch', 2 => 'Orc', 3 => 'Zwerg', 4 => 'Nachtelfe',
        5 => 'Untoter', 6 => 'Taure', 7 => 'Gnom', 8 => 'Troll',
        9 => 'Goblin', 10 => 'Blutelf', 11 => 'Draenei', 22 => 'Worgen',
        24 => 'Pandaren (Neutral)', 25 => 'Pandaren (Alliance)', 26 => 'Pandaren (Horde)',
        27 => 'Nightborne', 28 => 'Highmountain Tauren', 29 => 'Void Elf', 30 => 'Lightforged Draenei',
        31 => 'Zandalari Troll', 32 => 'Kul Tiran', 34 => 'Dark Iron Dwarf', 35 => 'Vulpera',
        36 => 'Mag’har Orc', 37 => 'Mechagnome', 52 => 'Dracthyr', 70 => 'Dracthyr'
    ];

    private function getClassName($classId)
    {
        return $this->classes[$classId] ?? 'Unknown Class';
    }

    private function getRaceName($raceId)
    {
        return $this->races[$raceId] ?? 'Unknown Race';
    }

    private function getAccessToken()
    {
        $clientId = config('services.battlenet.client_id');
        $clientSecret = config('services.battlenet.client_secret');

        if (!$clientId || !$clientSecret) {
            throw new \Exception("Blizzard API credentials are missing.");
        }

        return Cache::remember('blizzard_access_token', 3600, function () use ($clientId, $clientSecret) {
            $response = Http::asForm()
                ->withBasicAuth($clientId, $clientSecret)
                ->post("https://oauth.battle.net/token", [
                    'grant_type' => 'client_credentials',
                ]);

            $json = $response->json();

            \Log::error('Blizzard API Token Response: ', $json);

            if (!isset($json['access_token'])) {
                throw new \Exception("Blizzard API Error: " . json_encode($json));
            }

            return $json['access_token'];
        });
    }

    public function getGuildRoster()
    {
        // Check if cache exists & if it’s older than 30 minutes
        $lastUpdated = Cache::get('guild_roster_updated_at');

        if ($lastUpdated && Carbon::parse($lastUpdated)->diffInMinutes(now()) < 30) {
            return Cache::get('guild_roster'); // Use cached version
        }

        // If cache is expired or doesn't exist, fetch new data
        return Cache::remember('guild_roster', now()->addMinutes(30), function () {
            $accessToken = $this->getAccessToken();
            $realmSlug = strtolower(env('REALM_NAME'));
            $guildName = strtolower(env('GUILD_NAME'));

            $url = "https://eu.api.blizzard.com/data/wow/guild/{$realmSlug}/{$guildName}/roster";

            $response = Http::withToken($accessToken)
                ->withHeaders([
                    'User-Agent' => 'MyLaravelApp/1.0',
                    'Accept' => 'application/json',
                ])
                ->get($url, [
                    'namespace' => 'profile-eu',
                    'locale' => 'en_GB'
                ]);

            $data = json_decode($response->body(), true);

            if (!isset($data['members'])) {
                throw new \Exception("Blizzard API did not return members: " . json_encode($data));
            }

            $members = $data['members'];

            foreach ($members as &$member) {
                $charName = strtolower($member['character']['name']);
                $charRealm = $member['character']['realm']['slug'];

                // Get character profile picture
                $profileUrl = "https://eu.api.blizzard.com/profile/wow/character/{$charRealm}/{$charName}/character-media";

                $profileResponse = Http::withToken($accessToken)
                    ->get($profileUrl, [
                        'namespace' => 'profile-eu',
                        'locale' => 'en_GB'
                    ]);

                $profileData = json_decode($profileResponse->body(), true);
                $member['character']['avatar'] = $profileData['assets'][0]['value'] ?? null;

                // Get class and race names
                $classId = $member['character']['playable_class']['id'] ?? null;
                $raceId = $member['character']['playable_race']['id'] ?? null;

                $member['character']['class_name'] = $this->getClassName($classId);
                $member['character']['race_name'] = $this->getRaceName($raceId);
            }

            // Store last update timestamp
            Cache::put('guild_roster_updated_at', now(), now()->addMinutes(30));

            return ['members' => $members];
        });
    }

    public function getCharacterMedia($realmSlug, $characterName)
    {
        $accessToken = Auth::user()->battlenet_token; // Ensure user is authenticated via Battle.net

        $url = "https://eu.api.blizzard.com/profile/wow/character/{$realmSlug}/{$characterName}/character-media?namespace=profile-eu&locale=de_DE";

        $response = Http::withToken($accessToken)->get($url);

        $data = $response->json();

        if (!isset($data['assets'])) {
            \Log::error("Failed to fetch character media: " . json_encode($data));
            return asset('images/default-avatar.jpg'); // Fallback image
        }

        // Extract avatar URL
        foreach ($data['assets'] as $asset) {
            if ($asset['key'] === 'avatar') {
                return $asset['value'];
            }
        }

        return asset('images/default-avatar.jpg');
    }

}
