<?php

namespace App\Http\Controllers;

use App\Services\GuildService;
use Illuminate\Http\Request;

class GuildController extends Controller
{
    protected $guildService;

    public function __construct(GuildService $guildService)
    {
        $this->guildService = $guildService;
    }

    public function show()
    {
        $roster = $this->guildService->getGuildRoster();

        \Log::info('Guild Roster Response:', $roster);

        return view('dashboard.guild-overview', compact('roster'));
    }

}
