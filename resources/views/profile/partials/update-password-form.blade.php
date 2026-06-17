<section>
    <header class="mb-4">
        <h2 class="text-lg font-bold text-slate-900">Atualizar senha</h2>
        <p class="mt-1 text-sm text-slate-600">Use uma senha longa e única para manter sua conta segura.</p>
    </header>

    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        @method('put')

        <div>
            <x-input-label for="current_password" :value="'Senha atual'" />
            <x-text-input id="current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
        </div>

        <div>
            <x-input-label for="password" :value="'Nova senha'" />
            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="'Confirmar nova senha'" />
            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
            <x-gradient-button>Atualizar senha</x-gradient-button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-emerald-600">Senha atualizada.</p>
            @endif
        </div>
    </form>
</section>
