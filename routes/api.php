<?php
namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Models\SaveData;
use App\Http\Controllers\Api\SaveDataController;

Route::middleware('api')->get('/status', function (Request $request) {
    return response()->json(['message' => 'API is up and running!']);
});

Route::post('/save-gold', function (Request $request) {
    // Validate incoming JSON data
    $validated = $request->validate([
        'charName'         => 'required|string',
        'transactionType'  => 'required|integer',
        'AmountGold'       => 'nullable|string',
    ]);

    // Create a new record in the database
    $record = SaveData::create($validated);

    // Log for debugging
    Log::info('Data received:', $validated);

    // Return success response
    return response()->json([
        'message' => 'Data saved successfully',
        'data'    => $record
    ], 201);
});

Route::get('/save-gold', [SaveDataController::class, 'index']);


