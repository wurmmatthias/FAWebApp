<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Accountinformationen') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Editiere deine Accountinformationen und deinen Haupt-Charakter.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Name Field (Disabled for Battle.net Users) -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                :value="old('name', $user->name)" required autofocus autocomplete="name"
                @if($user->battlenet_id) disabled @endif />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Email Field (Disabled for Battle.net Users) -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                :value="old('email', $user->email)" required autocomplete="username"
                @if($user->battlenet_id) disabled @endif />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        @if ($user->battlenet_id)
            <div>
                <x-input-label for="main_character" :value="__('Hauptcharakter')" />
                <select id="main_character" name="main_character" class="mt-1 block w-full">
                    <option value="" {{ $user->main_character ? '' : 'selected' }}>
                        {{ __('Wähle deinen Hauptcharakter') }}
                    </option>

                    @forelse ($characters as $character)
                        @php
                            $classes = [
                                1 => 'Krieger', 2 => 'Paladin', 3 => 'Jäger', 4 => 'Schurke',
                                5 => 'Priester', 6 => 'Todesritter', 7 => 'Schamane', 8 => 'Magier',
                                9 => 'Hexenmeister', 10 => 'Mönch', 11 => 'Druide', 12 => 'Dämonenjäger',
                                13 => 'Rufer'
                            ];

                            $classId = $character['playable_class']['id'] ?? null;
                            $className = $classes[$classId] ?? 'Unbekannte Klasse';
                        @endphp
                        <option value="{{ json_encode($character) }}"
                            {{ $user->main_character && json_decode($user->main_character, true)['name'] === $character['name'] ? 'selected' : '' }}>
                            {{ $character['name'] }} (Level {{ $character['level'] }}) - Klasse: {{ $className }}
                        </option>
                    @empty
                        <option disabled>{{ __('Keine Charaktere in der Gilde gefunden.') }}</option>
                    @endforelse
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('main_character')" />
            </div>
        @endif

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Speichern') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition
                   x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Gespeichert.') }}
                </p>
            @endif
        </div>
    </form>
</section>
