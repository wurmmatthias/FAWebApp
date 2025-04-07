<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            💰 Neue Transaktion hinzufügen
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg" role="alert">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg" role="alert">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>⚠️ {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8">
                <form action="{{ route('admin.transactions.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="player_name" class="block font-medium text-gray-700 dark:text-gray-300">
                            🎮 Spielername
                        </label>
                        <input type="text" id="player_name" name="player_name" placeholder="Beispiel: Xyssa - Blutkessel"
                               class="w-full mt-2 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                               value="{{ old('player_name') }}" required>
                    </div>

                    <div>
                        <label for="amount" class="block font-medium text-gray-700 dark:text-gray-300">
                            💵 Betrag (in Kupfer)
                        </label>
                        <input type="number" id="amount" name="amount" placeholder="z.B. 10000 für 1 Gold"
                               class="w-full mt-2 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                               value="{{ old('amount') }}" min="1" required>
                        <p class="text-sm text-gray-500 mt-1">1 Gold = 10000 Kupfer | 1 Silber = 100 Kupfer</p>
                    </div>

                    <div>
                        <label for="type" class="block font-medium text-gray-700 dark:text-gray-300">
                            📂 Transaktionstyp
                        </label>
                        <select id="type" name="type" class="w-full mt-2 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" required>
                            <option value="">-- Bitte wählen --</option>
                            <option value="Deposit" {{ old('type') == 'Deposit' ? 'selected' : '' }}>Einzahlung</option>
                            <option value="Withdraw" {{ old('type') == 'Withdraw' ? 'selected' : '' }}>Abbuchung</option>
                        </select>
                    </div>

                    <div>
                        <label for="description" class="block font-medium text-gray-700 dark:text-gray-300">
                            📝 Beschreibung (optional)
                        </label>
                        <textarea id="description" name="description" rows="3"
                                  class="w-full mt-2 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                  placeholder="Weitere Details...">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label for="transaction_timestamp" class="block font-medium text-gray-700 dark:text-gray-300">
                            📅 Transaktionsdatum & Uhrzeit
                        </label>
                        <input type="datetime-local" id="transaction_timestamp" name="transaction_timestamp"
                               class="w-full mt-2 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                               value="{{ old('transaction_timestamp') ?? now()->format('Y-m-d\TH:i') }}" required>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg shadow-lg transition transform hover:scale-105">
                            ➕ Transaktion hinzufügen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
