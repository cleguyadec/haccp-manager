<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <!-- Actions -->
        <div class="flex flex-col gap-4">
            <button type="submit" class="block text-center bg-blue-600 hover:bg-blue-500 text-gray-800 font-medium py-2 px-4 rounded-md">
                {{ __('Connexion') }}
            </button>

            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="block text-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-md">
                    {{ __('Créer un compte') }}
                </a>
            @endif

            @if (Route::has('products.public'))
                <a href="{{ route('products.public') }}" class="block text-center bg-indigo-500 hover:bg-indigo-600 text-white font-medium py-2 px-4 rounded-md">
                    {{ __('Voir les produits') }}
                </a>
            @endif

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="block text-center text-sm text-gray-600 dark:text-gray-400 hover:underline">
                    {{ __('Mot de passe oublié ?') }}
                </a>
            @endif
        </div>
    </form>
</x-guest-layout>
