<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_name',
        'amount',
        'type',
        'description',
        'transaction_timestamp',
        'source'
    ];
}
