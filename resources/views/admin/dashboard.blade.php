<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">

            <!-- Navigation Buttons -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <a href="{{ route('admin.transactions') }}" class="btn-dashboard">
                    <span class="icon">💸</span>
                    <span>Transaktionen</span>
                </a>
                <a href="{{ route('admin.users') }}" class="btn-dashboard">
                    <span class="icon">🏰</span>
                    <span>Gilde verwalten</span>
                </a>
                <a href="{{ route('admin.settings') }}" class="btn-dashboard">
                    <span class="icon">⚙️</span>
                    <span>Einstellungen</span>
                </a>
            </div>

        </div>
    </div>

    <style>
        .btn-dashboard {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: #4f46e5;
            color: white;
            padding: 20px;
            border-radius: 10px;
            font-size: 1.2rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-dashboard:hover {
            background-color: #4338ca;
            transform: scale(1.05);
        }

        .btn-dashboard .icon {
            font-size: 2.5rem;
            margin-bottom: 8px;
        }
    </style>
</x-app-layout>
