<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GuildService;

class GuildController extends Controller
{
    protected $guildService;

    public function __construct(GuildService $guildService)
    {
        $this->guildService = $guildService;
    }

    public function show()
    {
        $roster = $this->guildService->getGuildRoster(); // Fetch the guild roster

        return view('dashboard.guild-overview', compact('roster'));
    }
}
