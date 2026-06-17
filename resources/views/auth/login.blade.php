<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <h1 class="text-xl font-bold text-slate-900 mb-4">Entrar</h1>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="'Email'" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="'Senha'" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-brand-blue shadow-sm focus:ring-brand-blue" name="remember">
                <span class="ms-2 text-sm text-slate-600">Lembrar de mim</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-6">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-slate-600 hover:text-brand-blue" href="{{ route('password.request') }}">
                    Esqueceu a senha?
                </a>
            @endif

            <a class="text-sm text-slate-600 hover:text-brand-blue" href="{{ route('register') }}">
                Criar conta
            </a>
        </div>

        <div class="mt-4">
            <x-primary-button class="w-full">Entrar</x-primary-button>
        </div>
    </form>
</x-guest-layout>
