<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Accountinformationen') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Editiere deine Accountinformationen und deinen Haupt-Charakter.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Name Field -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input
                id="name"
                name="name"
                type="text"
                class="mt-1 block w-full @if($user->battlenet_id) bg-gray-200 dark:bg-gray-700 cursor-not-allowed @endif"
                :value="old('name', $user->name)"
                @if($user->battlenet_id) disabled @endif
            />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Email Field -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input
                id="email"
                name="email"
                type="email"
                class="mt-1 block w-full @if($user->battlenet_id) bg-gray-200 dark:bg-gray-700 cursor-not-allowed @endif"
                :value="old('email', $user->email)"
                @if($user->battlenet_id) disabled @endif
            />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Deine E-Mail-Adresse ist nicht verifiziert.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Hier klicken, um die Verifizierungs-E-Mail erneut zu senden.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('Eine neue Verifizierungs-E-Mail wurde an deine Adresse gesendet.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        @if ($user->battlenet_id)
            <div>
                <x-input-label for="main_character" :value="__('Haupt-Charakter')" />
                <select id="main_character" name="main_character" class="mt-1 block w-full">
                    <option value="" {{ $user->main_character ? '' : 'selected' }}>
                        {{ __('Wähle deinen Haupt-Charakter') }}
                    </option>

                    @php
                        $classes = [
                            1 => 'Krieger', 2 => 'Paladin', 3 => 'Jäger', 4 => 'Schurke',
                            5 => 'Priester', 6 => 'Todesritter', 7 => 'Schamane', 8 => 'Magier',
                            9 => 'Hexenmeister', 10 => 'Mönch', 11 => 'Druide', 12 => 'Dämonenjäger',
                            13 => 'Rufer'
                        ];
                    @endphp

                    @forelse ($characters as $character)
                        @php
                            $classId = $character['playable_class']['id'] ?? null;
                            $className = $classes[$classId] ?? 'Unbekannte Klasse';
                        @endphp

                        <option value="{{ json_encode($character) }}"
                            {{ $user->main_character && json_decode($user->main_character, true)['name'] === $character['name'] ? 'selected' : '' }}>
                            {{ $character['name'] }} (Level {{ $character['level'] }}) - Klasse: {{ $className }}
                        </option>
                    @empty
                        <option disabled>{{ __('Keine Charaktere gefunden.') }}</option>
                    @endforelse
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('main_character')" />
            </div>
        @endif

        <!-- Save Button -->
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Speichern') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Gespeichert.') }}</p>
            @endif
        </div>
    </form>
</section>
