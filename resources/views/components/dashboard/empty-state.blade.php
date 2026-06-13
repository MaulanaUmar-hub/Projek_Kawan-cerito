@props([
    'title',
    'message',
    'actionLabel' => null,
    'actionUrl' => null,
])

<div class="kc-card p-8 text-center">
    <div class="mx-auto grid h-12 w-12 place-items-center rounded-lg bg-indigo-50 text-lg font-bold text-indigo-500">KC</div>
    <h2 class="mt-4 text-lg font-semibold text-kc-heading">{{ $title }}</h2>
    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">{{ $message }}</p>

    @if ($actionLabel && $actionUrl)
        <a href="{{ $actionUrl }}" class="mt-5 inline-flex rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">
            {{ $actionLabel }}
        </a>
    @endif
</div>
