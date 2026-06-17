@props(['textClass' => 'text-2xl'])

<a href="{{ url('/') }}" class="inline-flex items-center gap-2">
    <span class="h-9 w-9 rounded-xl bg-brand-gradient shadow-brand"></span>
    <span class="{{ $textClass }} font-extrabold tracking-tight text-brand-gradient">
        {{ config('app.name', 'Séries') }}
    </span>
</a>
