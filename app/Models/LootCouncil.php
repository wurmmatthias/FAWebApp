<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LootCouncil extends Model
{
    protected $fillable = [
        'player',
        'date',
        'time',
        'loot_id',
        'itemID',
        'itemString',
        'response',
        'votes',
        'class',
        'instance',
        'boss',
        'gear1',
        'gear2',
        'responseID',
        'isAwardReason',
        'rollType',
        'subType',
        'equipLoc',
        'note',
        'owner',
        'itemName',
        'servertime',
    ];
}
