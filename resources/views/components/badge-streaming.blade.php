@props(['service' => null])

@if ($service)
    <span
        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold text-white shadow-sm"
        style="background-color: {{ $service->color }}"
    >
        {{ $service->name }}
    </span>
@else
    <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-600">
        Sem streaming
    </span>
@endif
