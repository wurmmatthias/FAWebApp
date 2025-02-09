<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gegenstände Übersicht') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 mt-4 p-6 shadow-sm sm:rounded-lg">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Gegenstandshistorie</h3>

                <div class="space-y-4">
                    @php
                        // Dummy data with both deposits (green) and withdrawals (red)
                        $dummyData = [
                            ['charName' => 'Thrall', 'item' => 'Verzauberter Runenstoffbeutel', 'itemId' => 22249, 'amount' => 3, 'date' => '2025-02-09', 'type' => 'deposit'],
                            ['charName' => 'Sylvanas', 'item' => 'Schattenkugel', 'itemId' => 1254, 'amount' => 5, 'date' => '2025-02-08', 'type' => 'withdraw'],
                            ['charName' => 'Arthas', 'item' => 'Frostgram', 'itemId' => 19019, 'amount' => 1, 'date' => '2025-02-07', 'type' => 'withdraw'],
                            ['charName' => 'Jaina', 'item' => 'Arkane Kristalle', 'itemId' => 12363, 'amount' => 10, 'date' => '2025-02-06', 'type' => 'deposit'],
                            ['charName' => 'Gul\'dan', 'item' => 'Teufelsenergie-Splitter', 'itemId' => 22456, 'amount' => 7, 'date' => '2025-02-05', 'type' => 'withdraw'],
                        ];
                    @endphp

                    @foreach ($dummyData as $entry)
                        @php
                            $isDeposit = $entry['type'] === 'deposit';
                            $textColor = $isDeposit ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400';
                            $bgColor = $isDeposit ? 'bg-green-100 dark:bg-green-900' : 'bg-red-100 dark:bg-red-900';
                            $actionText = $isDeposit ? 'eingelagert' : 'entnommen';
                        @endphp

                        <div class="p-4 {{ $bgColor }} rounded-lg shadow-sm mb-2">
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="font-bold text-gray-900 dark:text-gray-100">{{ $entry['charName'] }}</span>
                                    <span class="text-gray-600 dark:text-gray-400">hat</span>
                                    <span class="font-semibold {{ $textColor }}">
                                        {{ $entry['amount'] }}x
                                        <a href="https://www.wowhead.com/item={{ $entry['itemId'] }}" target="_blank" class="hover:underline">
                                            {{ $entry['item'] }}
                                        </a>
                                    </span>
                                    <span class="text-gray-600 dark:text-gray-400">{{ $actionText }}.</span>
                                </div>
                                <div class="text-gray-500 dark:text-gray-300 text-sm">
                                    {{ $entry['date'] }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
