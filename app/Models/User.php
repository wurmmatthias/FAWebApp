<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'battlenet_id',
        'avatar',
    ];

        // Accessor to get avatar (Battle.net or default)
        public function getAvatarUrlAttribute()
        {
            return $this->avatar
                ? $this->avatar // Use Battle.net avatar if available
                : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&size=100'; // Default avatar
        }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getBattleNetCharacters()
    {
        if (!$this->battlenet_token) {
            Log::error('Battle.net token missing for user: ' . $this->id);
            return [];
        }

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->battlenet_token}"
        ])->get("https://eu.api.blizzard.com/profile/user/wow?namespace=profile-eu&locale=en_GB");

        Log::info('Battle.net API Response:', ['status' => $response->status(), 'data' => $response->json()]);

        if ($response->failed()) {
            Log::error('Battle.net API Failed:', ['response' => $response->json()]);
            return [];
        }

        return $response->json()['wow_accounts'][0]['characters'] ?? [];
    }
}
