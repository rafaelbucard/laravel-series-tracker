<section x-data="{ open: false }">
    <header class="mb-4">
        <h2 class="text-lg font-bold text-red-700">Excluir conta</h2>
        <p class="mt-1 text-sm text-slate-600">Esta ação é permanente. Suas séries, tópicos e comentários serão removidos.</p>
    </header>

    <x-danger-button @click="open = true">Excluir conta</x-danger-button>

    <div x-show="open" x-cloak class="mt-4 rounded-lg border border-red-200 bg-red-50 p-4">
        <form method="POST" action="{{ route('profile.destroy') }}" class="space-y-3">
            @csrf
            @method('delete')
            <p class="text-sm text-red-800">Confirme sua senha para excluir sua conta.</p>
            <div>
                <x-input-label for="password" :value="'Senha'" />
                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>
            <div class="flex gap-2">
                <button type="button" @click="open = false"
                        class="text-sm font-semibold text-slate-600 hover:text-slate-900">Cancelar</button>
                <x-danger-button>Confirmar exclusão</x-danger-button>
            </div>
        </form>
    </div>
</section>
