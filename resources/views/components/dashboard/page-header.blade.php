@props([
    'title',
    'subtitle' => null,
])

<div class="kc-card p-6 lg:p-8">
    <p class="text-sm font-semibold text-indigo-500">Kawan Cerito</p>
    <h2 class="mt-3 text-2xl font-bold text-kc-heading md:text-3xl">{{ $title }}</h2>

    @if ($subtitle)
        <p class="mt-3 max-w-3xl text-sm leading-6 text-slate-500">{{ $subtitle }}</p>
    @endif
</div>
