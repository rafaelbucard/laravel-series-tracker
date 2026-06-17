<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <x-series-cover :series="$series" class="w-16 shrink-0 aspect-[2/3]" />
            <div>
                <h1 class="text-2xl font-bold text-brand-gradient">{{ $series->name }}</h1>
                <div class="flex items-center gap-2 mt-1">
                    <x-badge-streaming :service="$series->streamingService" />
                    @if ($series->year)
                        <span class="text-xs text-slate-500">{{ $series->year }}</span>
                    @endif
                </div>
            </div>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-4">
        @if ($series->synopsis)
            <div class="rounded-xl bg-white border border-slate-200 shadow-sm p-4 text-sm text-slate-700">
                {{ $series->synopsis }}
            </div>
        @endif

        <div class="space-y-3">
            @foreach ($seasons as $season)
                @php
                    $total = $season->episodes->count();
                    $watched = $season->episodes->where('watched', true)->count();
                    $pct = $total > 0 ? (int) round(($watched / $total) * 100) : 0;
                @endphp
                <a href="{{ route('episodes.index', $season) }}"
                   class="block rounded-xl bg-white border border-slate-200 hover:border-brand-mid hover:shadow-brand transition p-4">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex-1">
                            <h2 class="font-bold text-slate-900">Temporada {{ $season->number }}</h2>
                            <p class="text-sm text-slate-500">{{ $watched }} / {{ $total }} episódios assistidos</p>
                        </div>
                        <div class="w-32 sm:w-48">
                            <x-progress-bar :percent="$pct" size="md" />
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</x-app-layout>
