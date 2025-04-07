<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Downloads') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-3xl font-bold text-gray-900 dark:text-gray-100 text-center">
                Jetzt seid ihr gefragt! 🚀
                </h3>
                <p class="mt-2 text-gray-600 dark:text-gray-300 text-center">
                Damit unsere Website und alle Features einwandfrei funktionieren, benötigt ihr die folgenden Addons.
                Ladet sie jetzt herunter und erlebt das volle Potenzial unserer Plattform! 🏆
            </p>

                @php
                $addons = [
                [
                    'name' => 'Raufasertapete Finanzamt',
                    'description' => 'Liest, speichert und visualisiert Daten aus der Gildenbank in den Saved Variables des WoW Clients.',
                    'version' => '1.0.0',
                    'updated' => 'Feb 2025',
                    'url' => '/downloads/elvui.zip',
                    'image' => 'adler.webp'
                ],
                [
                    'name' => 'Finanzamt Companion App (Windows / MacOs / Linux)',
                    'description' => 'Sendet Daten aus dem Finanzamt Add-On an die Web-API von Raufasertepte.',
                    'version' => '1.0.0',
                    'updated' => 'Feb 2025',
                    'url' => '/downloads/dbm.zip',
                    'image' => 'facomp.jpg'
                ],
    ];
                @endphp


                <div class="mt-8 space-y-6">
                    @foreach ($addons as $addon)
                        <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-lg flex flex-col md:flex-row items-center gap-6">
                            <img src="{{ asset('storage/' . $addon['image']) }}" alt="{{ $addon['name'] }}" class="w-48 h-48 object-cover rounded-lg shadow-lg">
                            <div class="flex-1">
                                <h4 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $addon['name'] }}</h4>
                                <p class="text-gray-600 dark:text-gray-300 mt-2">{{ $addon['description'] }}</p>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Version: {{ $addon['version'] }} | Updated: {{ $addon['updated'] }}
                                </p>
                                <a href="#"
                                   class="mt-4 inline-block px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition">
                                    Download
                                </a>
                                <a href="#"
                                   class="mt-4 inline-block px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-md transition">
                                    Dokumentation
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
