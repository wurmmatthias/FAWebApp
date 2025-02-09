<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Goldübersicht') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Erfolgreich angemeldet.") }}
                </div>
            </div>

            <!-- Total Gold Bar -->
            <div class="flex justify-between items-center bg-gray-900 text-yellow-400 p-4 rounded-lg mb-4 mt-4">
                <h3 class="text-lg font-semibold">Gesamtsaldo:</h3>
                <span class="flex items-center text-yellow-500 font-bold text-xl">
                    {{ isset($totalGold) ? number_format($totalGold) : 0 }}
                    <img src="https://wow.zamimg.com/images/wow/icons/large/inv_misc_coin_01.jpg" alt="Gold" class="w-6 h-6 ml-2">
                </span>
            </div>

            <div class="bg-white dark:bg-gray-800 mt-4 p-6 shadow-sm sm:rounded-lg">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Transaktionshistorie</h3>
                <ul>
                    @foreach ($transactions as $transaction)
                        @php
                            // Determine if it's a deposit or withdrawal
                            $transactionType = $transaction->transactionType == 1 ? 'Einzahlung' : 'Abbuchung';
                            $transactionColor = $transactionType === 'Einzahlung' ? 'text-green-500' : 'text-red-500';

                            // Assign a default character icon
                            $characterIcons = [
                                'Shaman' => 'https://wow.zamimg.com/images/wow/icons/large/classicon_shaman.jpg',
                                'Mage' => 'https://wow.zamimg.com/images/wow/icons/large/classicon_mage.jpg',
                                'Hunter' => 'https://wow.zamimg.com/images/wow/icons/large/classicon_hunter.jpg',
                                'Priest' => 'https://wow.zamimg.com/images/wow/icons/large/classicon_priest.jpg',
                                'Warlock' => 'https://wow.zamimg.com/images/wow/icons/large/classicon_warlock.jpg',
                                'Warrior' => 'https://wow.zamimg.com/images/wow/icons/large/classicon_warrior.jpg',
                                'Druid' => 'https://wow.zamimg.com/images/wow/icons/large/classicon_druid.jpg',
                                'Paladin' => 'https://wow.zamimg.com/images/wow/icons/large/classicon_paladin.jpg',
                                'Death Knight' => 'https://wow.zamimg.com/images/wow/icons/large/classicon_deathknight.jpg',
                                'Demon Hunter' => 'https://wow.zamimg.com/images/wow/icons/large/classicon_demonhunter.jpg'
                            ];

                            // Assign a random class icon
                            $characterIcon = $characterIcons[array_rand($characterIcons)];
                        @endphp

                        <li class="flex items-center justify-between border-b border-gray-700 pb-2 mb-2">
                            <!-- Left Side: Character Info -->
                            <div class="flex items-center space-x-4">
                                <img src="{{ $characterIcon }}" alt="{{ $transaction->charName }}" class="w-10 h-10 rounded-full">
                                <span class="{{ $transactionColor }} font-semibold">{{ $transactionType }}</span>
                                <span class="text-gray-900 dark:text-gray-100 font-medium">{{ $transaction->charName }}</span>
                            </div>

                            <!-- Right Side: Gold Amount (Color Based on Transaction Type) -->
                            <span class="flex items-center {{ $transactionColor }} font-bold">
                                @if ($transactionType === 'Einzahlung')
                                    <span class="font-bold pr-1">+</span>
                                @else
                                    <span class="font-bold pr-1">-</span>
                                @endif
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
