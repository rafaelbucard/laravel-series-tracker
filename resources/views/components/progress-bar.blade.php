@props(['percent' => 0, 'label' => null, 'size' => 'md'])

@php
    $percent = max(0, min(100, (int) $percent));
    $height = $size === 'sm' ? 'h-2' : ($size === 'lg' ? 'h-4' : 'h-3');
@endphp

<div {{ $attributes->merge(['class' => 'w-full']) }}>
    @if ($label)
        <div class="flex items-center justify-between mb-1 text-xs font-medium text-slate-600">
            <span>{{ $label }}</span>
            <span>{{ $percent }}%</span>
        </div>
    @endif
    <div class="w-full bg-slate-200 rounded-full {{ $height }} overflow-hidden">
        <div class="{{ $height }} rounded-full bg-brand-gradient transition-all" style="width: {{ $percent }}%"></div>
    </div>
</div>
