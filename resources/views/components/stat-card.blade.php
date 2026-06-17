@props(['label', 'value', 'icon' => null, 'accent' => 'gradient'])

@php
    $iconBg = match ($accent) {
        'red' => 'bg-red-100 text-red-600',
        'blue' => 'bg-blue-100 text-blue-600',
        'mid' => 'bg-fuchsia-100 text-fuchsia-700',
        default => 'bg-brand-gradient text-white',
    };
@endphp

<div {{ $attributes->merge(['class' => 'rounded-xl border border-slate-200 bg-white p-5 shadow-sm hover:shadow-md transition']) }}>
    <div class="flex items-center gap-4">
        <div class="flex h-12 w-12 items-center justify-center rounded-lg {{ $iconBg }}">
            @if ($icon)
                {!! $icon !!}
            @else
                <span class="font-bold">#</span>
            @endif
        </div>
        <div>
            <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
            <p class="text-2xl font-bold text-slate-900">{{ $value }}</p>
        </div>
    </div>
</div>
