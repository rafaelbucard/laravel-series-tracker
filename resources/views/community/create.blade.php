<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-brand-gradient">Novo tópico</h1>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <form action="{{ route('community.topics.store') }}" method="POST"
              class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-4">
            @csrf

            <div>
                <x-input-label for="title" :value="'Título'" />
                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required />
                <x-input-error :messages="$errors->get('title')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="series_id" :value="'Série relacionada (opcional)'" />
                <select id="series_id" name="series_id"
                        class="mt-1 block w-full rounded-lg border-slate-300 focus:border-brand-blue focus:ring-brand-blue">
                    <option value="">— sem série específica —</option>
                    @foreach ($userSeries as $s)
                        <option value="{{ $s->id }}" @selected(old('series_id') == $s->id)>{{ $s->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('series_id')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="body" :value="'Mensagem'" />
                <textarea id="body" name="body" rows="8" required
                          class="mt-1 block w-full rounded-lg border-slate-300 focus:border-brand-blue focus:ring-brand-blue">{{ old('body') }}</textarea>
                <x-input-error :messages="$errors->get('body')" class="mt-1" />
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('community.index') }}" class="text-sm font-semibold text-slate-600 hover:text-brand-blue">Cancelar</a>
                <x-gradient-button>Publicar</x-gradient-button>
            </div>
        </form>
    </div>
</x-app-layout>
