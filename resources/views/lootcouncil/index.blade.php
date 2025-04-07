<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('RCLootCouncil Historie') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    @auth
        @if (auth()->user()->role === 'admin')
            <!-- Upload JSON Form -->
            <form action="{{ route('lootcouncil.upload') }}" method="POST" enctype="multipart/form-data" class="mb-6 bg-gray-100 dark:bg-gray-700 p-4 rounded-xl">
                @csrf
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Upload Loot Council JSON File:
                </label>
                <div class="flex items-center gap-4">
                    <input type="file" name="json_file" accept=".json" required class="px-3 py-2 border border-gray-300 rounded-lg dark:bg-gray-800 dark:text-white">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                        Upload & Import
                    </button>
                </div>
            </form>
        @endif
    @endauth

    @if (session('success'))
        <div class="text-green-600 font-semibold mb-4">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="text-red-600 font-semibold mb-4">{{ session('error') }}</div>
    @endif

        <!-- Search -->
        <form method="GET" action="{{ route('lootcouncil.index') }}" class="mb-6">
            <input type="text" name="search" placeholder="Spieler oder Itemname suchen..." value="{{ request('search') }}"
                   class="px-4 py-2 w-full md:w-1/2 rounded-xl border border-gray-300 focus:ring focus:ring-indigo-200 dark:bg-gray-700 dark:text-white">
        </form>

        <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow-md rounded-2xl">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Datum</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Zeit</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Spieler</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Item</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Antwort</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Votes</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Klasse</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Boss</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Instance</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Note</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($lootHistory as $entry)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $entry->date }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $entry->time }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $entry->player }}</td>
                            <td class="px-4 py-3 text-sm text-indigo-600 dark:text-indigo-300">
                                <a href="https://www.wowhead.com/item={{ $entry->itemID }}" target="_blank" class="hover:underline">
                                    {{ $entry->itemName }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $entry->response }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $entry->votes }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                <img src="https://wow.zamimg.com/images/wow/icons/medium/class_{{ strtolower($entry->class) }}.jpg" alt="{{ $entry->class }}" class="h-5 w-5 rounded-full">
                                {{ $entry->class }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $entry->boss }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $entry->instance }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $entry->note }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $lootHistory->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>
