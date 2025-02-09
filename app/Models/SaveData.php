<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="SaveData",
 *     title="SaveData",
 *     description="SaveData model schema",
 *     type="object",
 *     required={"charName", "transactionType"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="charName", type="string", example="Hero123"),
 *     @OA\Property(property="transactionType", type="integer", example=1),
 *     @OA\Property(property="AmountGold", type="string", example="1000"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

class SaveData extends Model
{
    use HasFactory;

    // Explicitly define the table name (if needed)
    protected $table = 'save_data';

    protected $fillable = ['charName', 'transactionType', 'AmountGold'];
}
