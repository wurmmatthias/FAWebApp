<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SaveData;
use Illuminate\Http\Request;

class SaveDataController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/save-gold",
     *     summary="Save data",
     *     description="Stores data in the database",
     *     operationId="saveData",
     *     tags={"SaveData"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Pass data to be saved",
     *         @OA\JsonContent(
     *             required={"charName", "transactionType"},
     *             @OA\Property(property="charName", type="string", example="Hero123"),
     *             @OA\Property(property="transactionType", type="integer", example=1),
     *             @OA\Property(property="AmountGold", type="string", example="1000")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Data saved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Data saved successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     )
     * )
     */
    public function saveData(Request $request)
    {
        $validated = $request->validate([
            'charName'        => 'required|string',
            'transactionType' => 'required|integer',
            'AmountGold'      => 'nullable|string',
        ]);

        $record = SaveData::create($validated);
        Log::info('Data received:', $validated);

        return response()->json([
            'message' => 'Data saved successfully',
            'data'    => $record
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/save-gold",
     *     summary="Retrieve saved data",
     *     description="Returns all saved data",
     *     operationId="getSavedData",
     *     tags={"SaveData"},
     *     @OA\Response(
     *         response=200,
     *         description="Data retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Data retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/SaveData")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Data not found"
     *     )
     * )
     */
    public function index()
    {
        $data = SaveData::all();

        return response()->json([
            'message' => 'Data retrieved successfully',
            'data'    => $data
        ]);
    }
}
