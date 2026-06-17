@props(['active' => false])

@php
    $classes = $active
        ? 'inline-flex items-center px-1 pt-1 border-b-2 border-brand-mid text-sm font-semibold leading-5 text-brand-gradient focus:outline-none transition'
        : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-slate-600 hover:text-brand-blue hover:border-brand-blue/40 focus:outline-none transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
