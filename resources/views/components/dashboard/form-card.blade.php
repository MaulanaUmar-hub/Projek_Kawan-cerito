@props([
    'title',
    'description' => null,
])

<article {{ $attributes->merge(['class' => 'kc-card p-6']) }}>
    <div class="mb-6">
        <h2 class="text-lg font-semibold text-kc-heading">{{ $title }}</h2>

        @if ($description)
            <p class="mt-2 text-sm leading-6 text-slate-500">{{ $description }}</p>
        @endif
    </div>

    {{ $slot }}
</article>
