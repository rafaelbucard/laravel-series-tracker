<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-brand-gradient">Minhas séries</h1>
            <x-gradient-button :href="route('series.create')">+ Nova série</x-gradient-button>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        @if ($series->isEmpty())
            <div class="rounded-xl border-2 border-dashed border-slate-200 p-12 text-center">
                <p class="text-slate-600 mb-4">Você ainda não tem nenhuma série cadastrada.</p>
                <x-gradient-button :href="route('series.create')">Adicionar minha primeira série</x-gradient-button>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                @foreach ($series as $s)
                    @php
                        $percent = $s->episodes_total > 0
                            ? (int) round(($s->episodes_watched / $s->episodes_total) * 100)
                            : 0;
                    @endphp
                    <div class="rounded-xl bg-white border border-slate-200 overflow-hidden shadow-sm hover:shadow-brand transition flex flex-col">
                        <a href="{{ route('seasons.index', $s) }}">
                            <x-series-cover :series="$s" class="aspect-[2/3] w-full" />
                        </a>
                        <div class="p-4 flex-1 flex flex-col gap-2">
                            <div class="flex items-start justify-between gap-2">
                                <h3 class="font-bold text-slate-900 truncate flex-1">
                                    <a href="{{ route('seasons.index', $s) }}" class="hover:text-brand-blue">{{ $s->name }}</a>
                                </h3>
                                <x-badge-streaming :service="$s->streamingService" />
                            </div>
                            @if ($s->year)
                                <p class="text-xs text-slate-500">{{ $s->year }}</p>
                            @endif

                            <x-progress-bar :percent="$percent" :label="$s->episodes_watched . ' / ' . $s->episodes_total" size="sm" />

                            <div class="mt-2 flex items-center justify-between gap-2 pt-2 border-t border-slate-100">
                                <a href="{{ route('series.edit', $s) }}" class="text-xs font-semibold text-slate-500 hover:text-brand-blue">Editar</a>
                                <form action="{{ route('series.destroy', $s) }}" method="POST" onsubmit="return confirm('Remover esta série?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs font-semibold text-red-500 hover:text-red-700">Excluir</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
