@php($depth = $depth ?? 0)

<div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 {{ $depth > 0 ? 'ml-6' : '' }}"
     x-data="{ replying: false }">
    <div class="flex items-center gap-3 mb-2">
        @if ($comment->user->avatarUrl())
            <img src="{{ $comment->user->avatarUrl() }}" alt="" class="h-8 w-8 rounded-full object-cover">
        @else
            <div class="h-8 w-8 rounded-full bg-brand-gradient"></div>
        @endif
        <div>
            <a href="{{ route('users.show', $comment->user) }}" class="font-semibold text-slate-900 text-sm hover:text-brand-blue">{{ $comment->user->name }}</a>
            <p class="text-xs text-slate-500">{{ $comment->created_at->diffForHumans() }}</p>
        </div>
    </div>

    <div class="whitespace-pre-wrap text-sm text-slate-700">{{ $comment->body }}</div>

    @if ($depth === 0)
        <div class="mt-2">
            <button type="button" @click="replying = !replying"
                    class="text-xs font-semibold text-slate-500 hover:text-brand-blue">
                <span x-show="!replying">↪ responder</span>
                <span x-show="replying">cancelar</span>
            </button>

            <form x-show="replying" x-cloak action="{{ route('community.comments.store', $topic) }}" method="POST" class="mt-2">
                @csrf
                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                <textarea name="body" rows="2" required
                          class="block w-full rounded-lg border-slate-300 focus:border-brand-blue focus:ring-brand-blue text-sm"></textarea>
                <div class="flex justify-end mt-2">
                    <x-gradient-button class="text-xs">Responder</x-gradient-button>
                </div>
            </form>
        </div>
    @endif

    @if ($depth === 0 && $comment->replies->isNotEmpty())
        <div class="mt-3 space-y-2">
            @foreach ($comment->replies as $reply)
                @include('community.partials.comment', ['comment' => $reply, 'topic' => $topic, 'depth' => 1])
            @endforeach
        </div>
    @endif
</div>
