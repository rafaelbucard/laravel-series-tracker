<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('community.index') }}" class="text-sm text-slate-500 hover:text-brand-blue">← Comunidade</a>
        <h1 class="text-2xl font-bold text-brand-gradient mt-1">{{ $topic->title }}</h1>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">
        <article class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    @if ($topic->user->avatarUrl())
                        <img src="{{ $topic->user->avatarUrl() }}" alt="" class="h-10 w-10 rounded-full object-cover">
                    @else
                        <div class="h-10 w-10 rounded-full bg-brand-gradient"></div>
                    @endif
                    <div>
                        <a href="{{ route('users.show', $topic->user) }}" class="font-semibold text-slate-900 hover:text-brand-blue">{{ $topic->user->name }}</a>
                        <p class="text-xs text-slate-500">{{ $topic->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @if ($topic->series)
                    <span class="inline-flex items-center rounded-full bg-brand-gradient-soft px-3 py-1 text-xs font-semibold text-brand-mid">{{ $topic->series->name }}</span>
                @endif
            </div>
            <div class="whitespace-pre-wrap text-slate-800">{{ $topic->body }}</div>
        </article>

        <section class="space-y-3">
            <h2 class="font-bold text-slate-900">{{ $topic->comments->count() }} comentário{{ $topic->comments->count() === 1 ? '' : 's' }}</h2>

            @foreach ($topic->rootComments as $comment)
                @include('community.partials.comment', ['comment' => $comment, 'topic' => $topic, 'depth' => 0])
            @endforeach
        </section>

        <form action="{{ route('community.comments.store', $topic) }}" method="POST"
              class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            @csrf
            <x-input-label for="body" :value="'Sua resposta'" />
            <textarea id="body" name="body" rows="4" required
                      class="mt-1 block w-full rounded-lg border-slate-300 focus:border-brand-blue focus:ring-brand-blue">{{ old('body') }}</textarea>
            <x-input-error :messages="$errors->get('body')" class="mt-1" />
            <div class="flex items-center justify-end mt-3">
                <x-gradient-button>Comentar</x-gradient-button>
            </div>
        </form>
    </div>
</x-app-layout>
