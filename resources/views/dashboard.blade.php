<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Goldübersicht') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Gesamtübersicht -->
            <div class="bg-white dark:bg-gray-800 p-6 shadow-sm sm:rounded-lg">
                @if (!empty($mainCharacter))
                    @php
                        $mainCharacter = json_decode($user->main_character, true);
                        $classes = [
                            1 => 'Krieger', 2 => 'Paladin', 3 => 'Jäger', 4 => 'Schurke',
                            5 => 'Priester', 6 => 'Todesritter', 7 => 'Schamane', 8 => 'Magier',
                            9 => 'Hexenmeister', 10 => 'Mönch', 11 => 'Druide', 12 => 'Dämonenjäger',
                            13 => 'Rufer'
                        ];
                        $classId = $mainCharacter['playable_class']['id'] ?? null;
                        $className = $classes[$classId] ?? 'Unbekannte Klasse';

                        // Monthly gold payment status (Replace this with real logic)
                        $monthlyGoldReceived = rand(0, 1);
                        $statusIcon = $monthlyGoldReceived ? '✅' : '❌';

                    @endphp

                    <div class="flex items-center bg-gray-800 text-white p-4 rounded-lg">
                        <!-- Character Image -->
                        <img src="{{ $mainCharacter['avatar'] ?? asset('images/default-avatar.jpg') }}"
                            alt="{{ $mainCharacter['name'] }}"
                            class="w-20 h-20 rounded-full border-2 border-gray-600">

                        <!-- Character Info -->
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold">{{ $mainCharacter['name'] }} - {{ ucfirst($mainCharacter['realm']['slug'] ?? '') }}</h3>
                            <p class="text-sm">Level: <span class="font-semibold">{{ $mainCharacter['level'] }}</span></p>
                            <p class="text-sm">Klasse: <span class="font-semibold">{{ $className }}</span></p>
                        </div>

                        <!-- Status Icon -->
                        <div class="ml-auto">
                            <span class="{{ $monthlyGoldReceived ? 'text-green-500' : 'text-red-500' }} text-2xl">
                                {{ $statusIcon }}
                            </span>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500">Willkommen zurück! Solltest du via Battle.Net authentifiziert sein, kannst du deinen Hauptcharakter in den Profileinstellungen wählen.</p>
                @endif
            </div>

            <!-- Transactions of Main Character -->
            <div class="bg-white dark:bg-gray-800 p-6 shadow-sm sm:rounded-lg">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Transaktionen deines Hauptcharakters</h3>
                <ul class="space-y-2">
                @php
    $searchstring = isset($mainCharacter['name'], $mainCharacter['realm']['slug'])
        ? $mainCharacter['name'] . " - " . ucfirst($mainCharacter['realm']['slug'])
        : '';
            @endphp

                @forelse ($transactions->where('player_name', $searchstring ?? '') as $transaction)
                @php
                            // Convert amount to gold, silver, and copper
                            $gold = floor($transaction->amount / 10000);
                            $silver = floor(($transaction->amount % 10000) / 100);
                            $copper = $transaction->amount % 100;

                            $iconClass = $transaction->source === 'manual'
                ? 'fa-solid fa-file-pen text-red-500' // Manual transactions 📝
                : ''; // API transactions 💰

                        @endphp

                        <li class="flex justify-between items-center border-b border-gray-700 pb-2">
                            <span class="{{ $transaction->type === 'Deposit' ? 'text-green-500' : 'text-red-500' }} font-semibold">
                            <i class="{{ $iconClass }} text-lg mr-2"></i> {{ $transaction->type === 'Deposit' ? 'Einzahlung' : 'Abbuchung' }}
                            </span>
                            <span class="text-gray-900 dark:text-gray-100 font-medium">{{ $transaction->player_name }}</span>

                            <span class="flex items-center font-bold {{ $transaction->type === 'Deposit' ? 'text-green-500' : 'text-red-500' }}">
                                {{ $transaction->type === 'Deposit' ? '+' : '-' }}

                                @if ($gold > 0)
                                    <span class="ml-1">{{ $gold }}</span>
                                    <img src="https://wow.zamimg.com/images/wow/icons/large/inv_misc_coin_01.jpg" alt="Gold" class="w-5 h-5 ml-1">
                                @endif

                                @if ($silver > 0 || ($gold == 0 && $copper > 0))
                                    <span class="ml-1">{{ $silver }}</span>
                                    <img src="https://wow.zamimg.com/images/wow/icons/large/inv_misc_coin_03.jpg" alt="Silver" class="w-5 h-5 ml-1">
                                @endif

                                @if ($copper > 0 || ($gold == 0 && $silver == 0))
                                    <span class="ml-1">{{ $copper }}</span>
                                    <img src="https://wow.zamimg.com/images/wow/icons/large/inv_misc_coin_19.jpg" alt="Copper" class="w-5 h-5 ml-1">
                                @endif
                            </span>
                        </li>
                    @empty
                        <p class="text-gray-500">Keine Transaktionen für deinen Hauptcharakter gefunden.</p>
                    @endforelse
                </ul>
            </div>

        <!-- Gesamttransaktionshistorie -->
        <div class="bg-white dark:bg-gray-800 p-6 shadow-sm sm:rounded-lg">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Transaktionshistorie</h3>
            <ul class="space-y-2">
                @forelse ($allTransactions as $transaction) <!-- Ensure it's using $allTransactions -->
                    @php
                        // Convert amount to gold, silver, and copper
                        $gold = floor($transaction->amount / 10000);
                        $silver = floor(($transaction->amount % 10000) / 100);
                        $copper = $transaction->amount % 100;

                        $iconClass = $transaction->source === 'manual'
                ? 'fa-solid fa-file-pen text-red-500' // Manual transactions 📝
                : ''; // API transactions 💰
                    @endphp

                    <li class="flex justify-between items-center border-b border-gray-700 pb-2">
                        <span class="{{ $transaction->type === 'Deposit' ? 'text-green-500' : 'text-red-500' }} font-semibold">
                        <i class="{{ $iconClass }} text-lg mr-2"></i> {{ $transaction->type === 'Deposit' ? 'Einzahlung' : 'Abbuchung' }}
                        </span>
                        <span class="text-gray-900 dark:text-gray-100 font-medium">{{ $transaction->player_name }}</span>

                        <span class="flex items-center font-bold {{ $transaction->type === 'Deposit' ? 'text-green-500' : 'text-red-500' }}">
                            {{ $transaction->type === 'Deposit' ? '+' : '-' }}

                            @if ($gold > 0)
                                <span class="ml-1">{{ $gold }}</span>
                                <img src="https://wow.zamimg.com/images/wow/icons/large/inv_misc_coin_01.jpg" alt="Gold" class="w-5 h-5 ml-1">
                            @endif

                            @if ($silver > 0 || ($gold == 0 && $copper > 0))
                                <span class="ml-1">{{ $silver }}</span>
                                <img src="https://wow.zamimg.com/images/wow/icons/large/inv_misc_coin_03.jpg" alt="Silver" class="w-5 h-5 ml-1">
                            @endif

                            @if ($copper > 0 || ($gold == 0 && $silver == 0))
                                <span class="ml-1">{{ $copper }}</span>
                                <img src="https://wow.zamimg.com/images/wow/icons/large/inv_misc_coin_19.jpg" alt="Copper" class="w-5 h-5 ml-1">
                            @endif
                        </span>
                    </li>
                @empty
                    <p class="text-gray-500">Keine Transaktionen gefunden.</p>
                @endforelse
            </ul>
        </div>



        </div>
    </div>
</x-app-layout>
