<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
 /**
 * Converts a raw value into gold, silver, and copper.
 *
 * @param int $rawValue
 * @return array
 */
private function convertToGoldSilverCopper($rawValue)
{
    $gold = floor($rawValue / 10000); // 1 gold = 10,000 copper
    $silver = floor(($rawValue % 10000) / 100); // 1 silver = 100 copper
    $copper = $rawValue % 100; // Remaining copper

    return [
        'gold' => $gold,
        'silver' => $silver,
        'copper' => $copper,
    ];
}

/**
 * @OA\Post(
 *     path="/api/transactions",
 *     summary="Upload a new transaction",
 *     tags={"Transactions"},
 *     description="Stores a new transaction entry in the database",
 *     operationId="uploadTransaction",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"player_name", "amount", "type", "transaction_timestamp"},
 *             @OA\Property(property="player_name", type="string", example="Xyssa - Blutkessel"),
 *             @OA\Property(property="amount", type="integer", example=10000, description="Raw value in copper"),
 *             @OA\Property(property="type", type="string", example="Deposit"),
 *             @OA\Property(property="description", type="string", example="Player-578-0ABE9596"),
 *             @OA\Property(property="transaction_timestamp", type="string", format="date-time", example="2025-02-16 11:36:24")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Transaction successfully created",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="player_name", type="string", example="Xyssa - Blutkessel"),
 *             @OA\Property(property="gold", type="integer", example=1),
 *             @OA\Property(property="silver", type="integer", example=0),
 *             @OA\Property(property="copper", type="integer", example=0),
 *             @OA\Property(property="type", type="string", example="Deposit"),
 *             @OA\Property(property="description", type="string", example="Player-578-0ABE9596"),
 *             @OA\Property(property="transaction_timestamp", type="string", format="date-time", example="2025-02-16 11:36:24")
 *         )
 *     )
 * )
 */
public function store(Request $request)
{

    Log::info('Received Method:', ['method' => $request->method()]);
    Log::info('Received Headers:', $request->header());
    Log::info('Received Data:', $request->all());

    $validated = $request->validate([
        'player_name' => 'required|string',
        'amount' => 'required|integer',
        'type' => 'required|string|in:Deposit,Withdraw',
        'description' => 'nullable|string',
        'transaction_timestamp' => 'required|date',
    ]);

    // Store the transaction (amount is stored in copper)
    $transaction = Transaction::create([
        'player_name' => $validated['player_name'],
        'amount' => $validated['amount'], // Store as copper only!
        'type' => $validated['type'],
        'description' => $validated['description'] ?? null,
        'transaction_timestamp' => $validated['transaction_timestamp'],
    ]);

    return response()->json($transaction, 201);
}

public function storeManually(Request $request)
{
    $request->validate([
        'player_name' => 'required|string|max:255',
        'amount' => 'required|integer|min:1',
        'type' => 'required|in:Deposit,Withdraw',
        'description' => 'nullable|string|max:500',
        'transaction_timestamp' => 'required|date',
    ]);

    Transaction::create([
        'player_name' => $request->player_name,
        'amount' => $request->amount,
        'type' => $request->type,
        'description' => $request->description,
        'transaction_timestamp' => $request->transaction_timestamp,
        'source' => 'manual', // Manually added transactions
    ]);

    return redirect()->route('admin.transactions')->with('success', '✅ Transaktion erfolgreich hinzugefügt!');
}



/**
 * @OA\Get(
 *     path="/api/transactions",
 *     summary="Get all transactions",
 *     tags={"Transactions"},
 *     description="Retrieve a list of all stored transactions with gold, silver, and copper",
 *     operationId="getTransactions",
 *     @OA\Response(
 *         response=200,
 *         description="Successful response",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="player_name", type="string", example="Xyssa - Blutkessel"),
 *                 @OA\Property(property="gold", type="integer", example=1),
 *                 @OA\Property(property="silver", type="integer", example=0),
 *                 @OA\Property(property="copper", type="integer", example=0),
 *                 @OA\Property(property="type", type="string", example="Deposit"),
 *                 @OA\Property(property="description", type="string", example="Player-578-0ABE9596"),
 *                 @OA\Property(property="transaction_timestamp", type="string", format="date-time", example="2025-02-16 11:36:24")
 *             )
 *         )
 *     )
 * )
 */
public function index()
{
    $transactions = Transaction::orderBy('transaction_timestamp', 'desc')->get();

    return response()->json($transactions, 200);
}
}
