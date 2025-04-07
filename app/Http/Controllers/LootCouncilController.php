<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LootCouncil;


class LootCouncilController extends Controller
{
    public function index(Request $request)
    {
        $query = LootCouncil::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('player', 'like', "%$search%")
                  ->orWhere('itemName', 'like', "%$search%");
        }

        $lootHistory = $query->orderBy('date', 'desc')->paginate(15);

        return view('lootcouncil.index', compact('lootHistory'));
    }

    public function upload(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'json_file' => 'required|mimes:json|max:2048',
        ]);

        try {
            $json = file_get_contents($request->file('json_file')->getRealPath());
            $data = json_decode($json, true);

            foreach ($data as $entry) {
                LootCouncil::updateOrCreate(
                    ['loot_id' => $entry['id']],
                    [
                        'player' => $entry['player'],
                        'date' => $entry['date'],
                        'time' => $entry['time'],
                        'itemID' => $entry['itemID'],
                        'itemString' => $entry['itemString'],
                        'response' => $entry['response'],
                        'votes' => $entry['votes'],
                        'class' => $entry['class'],
                        'instance' => $entry['instance'],
                        'boss' => $entry['boss'],
                        'gear1' => $entry['gear1'],
                        'gear2' => $entry['gear2'],
                        'responseID' => $entry['responseID'],
                        'isAwardReason' => $entry['isAwardReason'] === 'true',
                        'rollType' => $entry['rollType'],
                        'subType' => $entry['subType'],
                        'equipLoc' => $entry['equipLoc'],
                        'note' => $entry['note'],
                        'owner' => $entry['owner'],
                        'itemName' => $entry['itemName'],
                        'servertime' => $entry['servertime'],
                    ]
                );
            }

            return redirect()->back()->with('success', 'JSON file imported successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to import JSON file: ' . $e->getMessage());
        }
    }
}
