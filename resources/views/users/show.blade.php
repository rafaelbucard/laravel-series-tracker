<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            @if ($profileUser->avatarUrl())
                <img src="{{ $profileUser->avatarUrl() }}" alt="" class="h-16 w-16 rounded-full object-cover">
            @else
                <div class="h-16 w-16 rounded-full bg-brand-gradient"></div>
            @endif
            <div>
                <h1 class="text-2xl font-bold text-brand-gradient">{{ $profileUser->name }}</h1>
                @if ($profileUser->bio)
                    <p class="text-sm text-slate-600 mt-1">{{ $profileUser->bio }}</p>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat-card label="Séries" :value="$stats['series_count']" accent="gradient" />
            <x-stat-card label="Episódios assistidos" :value="$stats['episodes_watched']" accent="red" />
            <x-stat-card label="Tópicos" :value="$stats['topics_count']" accent="blue" />
            <x-stat-card label="Comentários" :value="$stats['comments_count']" accent="mid" />
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <h2 class="text-lg font-bold text-slate-900 mb-4">Séries de {{ $profileUser->name }}</h2>

            @if ($series->isEmpty())
                <p class="text-sm text-slate-500">Nenhuma série pública.</p>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                    @foreach ($series as $s)
                        <div class="block">
                            <x-series-cover :series="$s" class="aspect-[2/3]" />
                            <p class="mt-2 text-sm font-semibold text-slate-800 truncate">{{ $s->name }}</p>
                            <div class="mt-1">
                                <x-badge-streaming :service="$s->streamingService" />
                            </div>
                            @php
                                $pct = $s->episodes_total > 0
                                    ? (int) round(($s->episodes_watched / $s->episodes_total) * 100)
                                    : 0;
                            @endphp
                            <div class="mt-2">
                                <x-progress-bar :percent="$pct" size="sm" />
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
