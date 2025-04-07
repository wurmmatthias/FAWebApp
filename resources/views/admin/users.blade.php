<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            👥 Benutzerverwaltung
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 text-green-800 font-semibold rounded-lg p-4 mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    Benutzerübersicht
                </h3>

                <table class="w-full table-auto border-collapse">
                    <thead class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Name</th>
                            <th class="px-4 py-2 text-left">E-Mail</th>
                            <th class="px-4 py-2 text-center">Rolle</th>
                            <th class="px-4 py-2 text-center">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="border-b border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <td class="px-4 py-2">{{ $user->name }}</td>
                                <td class="px-4 py-2">{{ $user->email }}</td>
                                <td class="px-4 py-2 text-center">
                                    @if ($user->role === 'admin')
                                        <span class="text-green-500 font-bold">Admin</span>
                                    @else
                                        <span class="text-gray-600">Benutzer</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-center">
                                @if ($user->role === 'user')
                            <form method="POST" action="{{ route('admin.users.promote', $user) }}" class="inline-block">
                                @csrf
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg font-semibold">
                                    🆙 Befördern
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.users.revoke', $user) }}" class="inline-block">
                                @csrf
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg font-semibold">
                                    🔻 Rechte entziehen
                                </button>
                            </form>
                        @endif

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
