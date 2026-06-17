<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-brand-gradient">Dashboard</h1>
            <x-gradient-button :href="route('series.create')">+ Nova série</x-gradient-button>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8">
        @php
            $totals = $stats['totals'];
            $watchedPct = $totals['episodes_total'] > 0
                ? (int) round(($totals['episodes_watched'] / $totals['episodes_total']) * 100)
                : 0;
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat-card label="Séries" :value="$totals['series']" accent="gradient" />
            <x-stat-card label="Episódios assistidos" :value="$totals['episodes_watched'] . ' / ' . $totals['episodes_total']" accent="red" />
            <x-stat-card label="Séries finalizadas" :value="$totals['completed_series']" accent="blue" />
            <x-stat-card label="Progresso geral" :value="$watchedPct . '%'" accent="mid" />
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-slate-900">Continue assistindo</h2>
                <span class="text-sm text-slate-500">{{ $stats['continue_watching']->count() }} séries em andamento</span>
            </div>

            @if ($stats['continue_watching']->isEmpty())
                <p class="text-sm text-slate-500">Você não tem séries em andamento. Que tal começar uma nova?</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($stats['continue_watching'] as $item)
                        @php
                            $s = $item['series'];
                            $next = $item['next'];
                        @endphp
                        <a href="{{ route('episodes.index', $next['season_id']) }}" class="block group rounded-xl border border-slate-200 hover:border-brand-mid hover:shadow-brand transition p-4">
                            <div class="flex gap-4">
                                <x-series-cover :series="$s" class="w-16 shrink-0 aspect-[2/3]" />
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-slate-900 truncate group-hover:text-brand-blue">{{ $s->name }}</h3>
                                    <p class="text-xs text-slate-500 mb-2">
                                        Próximo: T{{ $next['season_number'] }} · E{{ $next['episode_number'] }}
                                    </p>
                                    <x-progress-bar :percent="$s->percent" size="sm" />
                                    <div class="mt-2">
                                        <x-badge-streaming :service="$s->streamingService" />
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900 mb-4">Atividade recente</h2>
                @if ($stats['recent_activity']->isEmpty())
                    <p class="text-sm text-slate-500">Marque episódios como assistidos para vê-los aqui.</p>
                @else
                    <ul class="divide-y divide-slate-100">
                        @foreach ($stats['recent_activity'] as $ep)
                            <li class="py-3 flex items-center justify-between text-sm">
                                <span class="text-slate-700">
                                    <span class="font-semibold">{{ $ep->season->series->name }}</span>
                                    · T{{ $ep->season->number }} · E{{ $ep->number }}
                                </span>
                                <span class="text-xs text-slate-400">{{ $ep->updated_at->diffForHumans() }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900 mb-4">Séries finalizadas</h2>
                @if ($stats['completed']->isEmpty())
                    <p class="text-sm text-slate-500">Nenhuma série finalizada ainda. Continue marcando episódios!</p>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach ($stats['completed'] as $s)
                            <a href="{{ route('seasons.index', $s) }}" class="block group">
                                <x-series-cover :series="$s" class="aspect-[2/3]" />
                                <p class="mt-1 text-xs font-semibold text-slate-700 truncate group-hover:text-brand-blue">{{ $s->name }}</p>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
