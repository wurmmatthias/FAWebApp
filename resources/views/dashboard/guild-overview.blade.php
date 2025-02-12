<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gildenübersicht & Mitglieder') }}
        </h2>
        <div class="text-gray-500 text-sm mb-2">
            Letztes Update: {{ \Carbon\Carbon::parse(Cache::get('guild_roster_updated_at', now()))->diffForHumans() }}
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

    <div class="bg-gray-800 shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-700">
            <thead class="bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Charakter</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Level</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Klasse</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Volk</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Realm</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Warcraft Logs</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @foreach($roster['members'] as $member)
                    @php
                        $character = $member['character'] ?? null;
                        $name = $character['name'] ?? 'Unknown';
                        $realmSlug = $character['realm']['slug'] ?? 'unknown-realm';
                        $level = $character['level'] ?? 'N/A';
                        $className = $character['class_name'] ?? 'Unknown Class';
                        $raceName = $character['race_name'] ?? 'Unknown Race';
                        $avatar = $character['avatar'] ?? 'https://via.placeholder.com/64'; // Default placeholder
                        $logsUrl = "https://www.warcraftlogs.com/character/eu/{$realmSlug}/{$name}";
                    @endphp

                    <tr class="bg-gray-800 hover:bg-gray-700 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap flex items-center">
                            <img src="{{ $avatar }}" alt="{{ $name }}" class="h-10 w-10 rounded-full mr-3">
                            <span class="text-white font-semibold">{{ $name }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-300">{{ $level }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-yellow-400">{{ $className }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-300">{{ $raceName }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-300">{{ ucfirst($realmSlug) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ $logsUrl }}" target="_blank" class="text-blue-400 hover:underline">Logs ansehen</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


</div>

</div>

</x-app-layout>
