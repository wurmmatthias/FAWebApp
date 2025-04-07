<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LootCouncil;


class LootCouncilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $data = json_decode(file_get_contents(storage_path('app/lctest.json')), true);

        foreach ($data as $entry) {
            LootCouncil::create([
                'player' => $entry['player'],
                'date' => $entry['date'],
                'time' => $entry['time'],
                'loot_id' => $entry['id'],
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
            ]);
        }
    }
}
