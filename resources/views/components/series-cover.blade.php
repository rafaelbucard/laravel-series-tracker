@props(['series', 'class' => 'aspect-[2/3] w-full'])

@php
    $url = $series?->coverDisplayUrl();
@endphp

<div {{ $attributes->merge(['class' => $class . ' overflow-hidden rounded-lg bg-brand-gradient-soft']) }}>
    @if ($url)
        <img src="{{ $url }}" alt="{{ $series->name }}" class="h-full w-full object-cover">
    @else
        <div class="flex h-full w-full items-center justify-center bg-brand-gradient-soft">
            <span class="px-2 text-center text-xs font-semibold text-brand-mid">{{ $series?->name }}</span>
        </div>
    @endif
</div>
