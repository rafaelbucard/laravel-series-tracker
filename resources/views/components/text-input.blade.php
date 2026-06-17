@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'block w-full rounded-lg border-slate-300 bg-white shadow-sm focus:border-brand-blue focus:ring-brand-blue']) }}>
