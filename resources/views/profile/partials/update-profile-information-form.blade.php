<section>
    <header class="mb-4">
        <h2 class="text-lg font-bold text-slate-900">Informações do perfil</h2>
        <p class="mt-1 text-sm text-slate-600">Atualize seu nome, email, avatar e biografia.</p>
    </header>

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('patch')

        <div class="flex items-center gap-4">
            @if (auth()->user()->avatarUrl())
                <img src="{{ auth()->user()->avatarUrl() }}" alt="" class="h-20 w-20 rounded-full object-cover">
            @else
                <div class="h-20 w-20 rounded-full bg-brand-gradient"></div>
            @endif
            <div class="flex-1">
                <x-input-label for="avatar" :value="'Avatar (imagem, até 2MB)'" />
                <input id="avatar" name="avatar" type="file" accept="image/*"
                       class="mt-1 block w-full text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-brand-gradient file:px-3 file:py-2 file:text-sm file:font-semibold file:text-white">
                <x-input-error :messages="$errors->get('avatar')" class="mt-1" />
            </div>
        </div>

        <div>
            <x-input-label for="name" :value="'Nome'" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <div>
            <x-input-label for="email" :value="'Email'" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div>
            <x-input-label for="bio" :value="'Bio'" />
            <textarea id="bio" name="bio" rows="3"
                      class="mt-1 block w-full rounded-lg border-slate-300 focus:border-brand-blue focus:ring-brand-blue">{{ old('bio', $user->bio) }}</textarea>
            <x-input-error :messages="$errors->get('bio')" class="mt-1" />
        </div>

        <div class="flex items-center gap-2">
            <input id="profile_is_public" name="profile_is_public" type="checkbox" value="1"
                   @checked(old('profile_is_public', $user->profile_is_public))
                   class="rounded border-slate-300 text-brand-mid focus:ring-brand-mid">
            <label for="profile_is_public" class="text-sm text-slate-700">Tornar meu perfil público (outros usuários podem ver minhas séries)</label>
        </div>

        <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
            <x-gradient-button>Salvar</x-gradient-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-emerald-600">Salvo.</p>
            @endif
        </div>
    </form>
</section>
