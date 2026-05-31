@props([
    'label',
    'value',
    'icon' => 'D',
    'color' => 'primary',
])

@php
    $colors = [
        'primary' => ['bg' => 'var(--kc-primary-soft)', 'text' => 'var(--kc-primary)'],
        'warning' => ['bg' => 'var(--kc-warning-soft)', 'text' => 'var(--kc-warning)'],
        'success' => ['bg' => 'var(--kc-success-soft)', 'text' => 'var(--kc-success)'],
        'info' => ['bg' => 'var(--kc-info-soft)', 'text' => 'var(--kc-info)'],
        'danger' => ['bg' => '#ffe0db', 'text' => '#ff3e1d'],
    ];

    $selected = $colors[$color] ?? $colors['primary'];
@endphp

<article class="kc-card p-5">
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-sm text-slate-400">{{ $label }}</p>
            <p class="mt-2 text-3xl font-bold text-kc-heading">{{ $value }}</p>
        </div>

        <div class="grid h-11 w-11 place-items-center rounded-lg text-lg font-bold" style="background:{{ $selected['bg'] }};color:{{ $selected['text'] }};">
            {{ $icon }}
        </div>
    </div>
</article>
