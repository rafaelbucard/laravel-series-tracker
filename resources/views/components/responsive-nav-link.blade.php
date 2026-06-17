@props(['active' => false])

@php
    $classes = $active
        ? 'block w-full pl-3 pr-4 py-2 border-l-4 border-brand-mid text-left text-base font-semibold text-brand-mid bg-brand-gradient-soft focus:outline-none transition'
        : 'block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-slate-600 hover:text-brand-blue hover:bg-slate-50 focus:outline-none transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
