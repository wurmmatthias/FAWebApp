<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gildenübersicht') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-semibold mb-4">Mitgliederliste</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-700 rounded-lg">
                            <thead class="bg-gray-900 text-gray-300">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-medium uppercase">Name</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium uppercase">Level</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium uppercase">Klasse</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium uppercase">Realm</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium uppercase">Warcraft Logs</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @forelse($roster['members'] ?? [] as $member)
                                    @php
                                        $character = $member['character'] ?? null;
                                        $name = $character['name'] ?? 'Unbekannt';
                                        $realmSlug = $character['realm']['slug'] ?? 'unknown-realm';
                                        $level = $character['level'] ?? 'N/A';
                                        $className = $character['class_name'] ?? 'Unbekannte Klasse';
                                        $raceName = $character['race_name'] ?? 'Unbekannte Rasse';
                                        $avatar = $character['avatar'] ?? 'https://via.placeholder.com/64'; // Default placeholder
                                        $logsUrl = "https://www.warcraftlogs.com/character/eu/{$realmSlug}/{$name}";
                                    @endphp
                                    <tr class="hover:bg-gray-700 transition">
                                        <td class="px-6 py-4 whitespace-nowrap flex items-center">
                                            <img src="{{ $avatar }}" alt="{{ $name }}" class="w-10 h-10 rounded-full border border-gray-500">
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-gray-400">{{ $name }}</div>
                                                <div class="text-xs text-gray-400">{{ $raceName }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $level }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $className }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ ucfirst($realmSlug) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="{{ $logsUrl }}" target="_blank" class="text-blue-400 hover:underline">
                                                Warcraft Logs
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            Keine Mitglieder in der Gilde gefunden.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
