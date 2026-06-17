@props(['type' => 'submit', 'href' => null])

@php
    $classes = 'inline-flex items-center justify-center gap-2 rounded-lg bg-brand-gradient px-5 py-2.5 text-sm font-semibold text-white shadow-brand transition hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-blue';
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button {{ $attributes->merge(['type' => $type, 'class' => $classes]) }}>{{ $slot }}</button>
@endif
