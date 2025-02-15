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
                @if ($user->main_character)
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
                            <h3 class="text-lg font-semibold">{{ $mainCharacter['name'] }}</h3>
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
                    @forelse ($transactions->where('charName', $mainCharacter['name'] ?? '') as $transaction)
                        @php
                            $transactionType = $transaction->transactionType == 1 ? 'Einzahlung' : 'Abbuchung';
                            $transactionColor = $transactionType === 'Einzahlung' ? 'text-green-500' : 'text-red-500';
                        @endphp

                        <li class="flex justify-between items-center border-b border-gray-700 pb-2">
                            <span class="{{ $transactionColor }} font-semibold">{{ $transactionType }}</span>
                            <span class="text-gray-900 dark:text-gray-100 font-medium">{{ $transaction->charName }}</span>
                            <span class="flex items-center {{ $transactionColor }} font-bold">
                                {{ $transactionType === 'Einzahlung' ? '+' : '-' }}
                                {{ number_format($transaction->AmountGold) }}
                                <img src="https://wow.zamimg.com/images/wow/icons/large/inv_misc_coin_01.jpg" alt="Gold" class="w-5 h-5 ml-1">
                            </span>
                        </li>
                    @empty
                        <p class="text-gray-500">Keine Transaktionen für deinen Hauptcharakter gefunden.</p>
                    @endforelse
                </ul>
            </div>

            <!-- Total Gold Section -->
            <div class="bg-gray-900 text-yellow-400 p-4 rounded-lg">
                <h3 class="text-lg font-semibold">Gesamtsaldo:</h3>
                <div class="flex items-center text-yellow-500 font-bold text-xl">
                    {{ isset($totalGold) ? number_format($totalGold) : 0 }}
                    <img src="https://wow.zamimg.com/images/wow/icons/large/inv_misc_coin_01.jpg" alt="Gold" class="w-6 h-6 ml-2">
                </div>
            </div>

            <!-- Gesamttransaktionshistorie -->
            <div class="bg-white dark:bg-gray-800 p-6 shadow-sm sm:rounded-lg">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Transaktionshistorie</h3>
                <ul class="space-y-2">
                    @foreach ($transactions as $transaction)
                        @php
                            $transactionType = $transaction->transactionType == 1 ? 'Einzahlung' : 'Abbuchung';
                            $transactionColor = $transactionType === 'Einzahlung' ? 'text-green-500' : 'text-red-500';
                        @endphp

                        <li class="flex justify-between items-center border-b border-gray-700 pb-2">
                            <span class="{{ $transactionColor }} font-semibold">{{ $transactionType }}</span>
                            <span class="text-gray-900 dark:text-gray-100 font-medium">{{ $transaction->charName }}</span>
                            <span class="flex items-center {{ $transactionColor }} font-bold">
                                {{ $transactionType === 'Einzahlung' ? '+' : '-' }}
                                {{ number_format($transaction->AmountGold) }}
                                <img src="https://wow.zamimg.com/images/wow/icons/large/inv_misc_coin_01.jpg" alt="Gold" class="w-5 h-5 ml-1">
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>

        </div>
    </div>
</x-app-layout>
