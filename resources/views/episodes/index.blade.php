<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('seasons.index', $season->series) }}" class="text-sm text-slate-500 hover:text-brand-blue">← {{ $season->series->name }}</a>
            <h1 class="text-2xl font-bold text-brand-gradient">Temporada {{ $season->number }}</h1>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <form action="{{ route('episodes.update', $season) }}" method="POST"
              class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            @csrf

            <ul class="divide-y divide-slate-100">
                @foreach ($episodes as $ep)
                    <li class="py-3">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="episodes[]" value="{{ $ep->id }}"
                                   @checked($ep->watched)
                                   class="h-5 w-5 rounded border-slate-300 text-brand-mid focus:ring-brand-mid">
                            <span class="font-medium text-slate-700">Episódio {{ $ep->number }}</span>
                        </label>
                    </li>
                @endforeach
            </ul>

            <div class="flex items-center justify-end gap-3 pt-4 mt-4 border-t border-slate-100">
                <x-gradient-button>Salvar progresso</x-gradient-button>
            </div>
        </form>
    </div>
</x-app-layout>
