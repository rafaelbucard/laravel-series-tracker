<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-brand-gradient">Comunidade</h1>
            <x-gradient-button :href="route('community.topics.create')">+ Novo tópico</x-gradient-button>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-4">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <form method="GET" action="{{ route('community.index') }}" class="flex flex-wrap items-end gap-3">
                <div class="flex items-center gap-2 text-sm">
                    <label class="font-semibold text-slate-700">Ordenar:</label>
                    <a href="{{ route('community.index', ['sort' => 'recent']) }}"
                       class="{{ $sort === 'recent' ? 'text-brand-mid font-semibold underline' : 'text-slate-500 hover:text-brand-blue' }}">Recentes</a>
                    <span class="text-slate-300">·</span>
                    <a href="{{ route('community.index', ['sort' => 'commented']) }}"
                       class="{{ $sort === 'commented' ? 'text-brand-mid font-semibold underline' : 'text-slate-500 hover:text-brand-blue' }}">Mais comentados</a>
                </div>
            </form>
        </div>

        @forelse ($topics as $topic)
            <a href="{{ route('community.topics.show', $topic) }}"
               class="block bg-white rounded-xl border border-slate-200 hover:border-brand-mid hover:shadow-brand transition p-5">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <h2 class="text-lg font-bold text-slate-900 hover:text-brand-blue truncate">{{ $topic->title }}</h2>
                        <p class="text-sm text-slate-500 mt-1 line-clamp-2">{{ \Illuminate\Support\Str::limit($topic->body, 220) }}</p>
                        <div class="flex flex-wrap items-center gap-2 mt-3 text-xs text-slate-500">
                            <span>por <span class="font-semibold text-brand-blue">{{ $topic->user->name }}</span></span>
                            <span>·</span>
                            <span>{{ $topic->created_at->diffForHumans() }}</span>
                            @if ($topic->series)
                                <span>·</span>
                                <span class="inline-flex items-center rounded-full bg-brand-gradient-soft px-2 py-0.5 font-semibold text-brand-mid">{{ $topic->series->name }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-center shrink-0">
                        <div class="text-2xl font-bold text-brand-mid">{{ $topic->comments_count }}</div>
                        <div class="text-[10px] uppercase tracking-wide text-slate-400">comentários</div>
                    </div>
                </div>
            </a>
        @empty
            <div class="rounded-xl border-2 border-dashed border-slate-200 p-12 text-center">
                <p class="text-slate-600 mb-4">Nenhum tópico ainda. Que tal abrir o primeiro?</p>
                <x-gradient-button :href="route('community.topics.create')">Criar tópico</x-gradient-button>
            </div>
        @endforelse

        <div>{{ $topics->links() }}</div>
    </div>
</x-app-layout>
