@props(['entries' => []])

@php
    $manifestPath = public_path('build/manifest.json');
    $manifest = file_exists($manifestPath)
        ? json_decode(file_get_contents($manifestPath), true)
        : null;
@endphp

@if ($manifest)
    @foreach ((array) $entries as $entry)
        @php($asset = $manifest[$entry]['file'] ?? null)

        @if ($asset && str_ends_with($asset, '.css'))
            <link rel="stylesheet" href="/build/{{ $asset }}">
        @elseif ($asset && str_ends_with($asset, '.js'))
            <script type="module" src="/build/{{ $asset }}"></script>
        @endif
    @endforeach
@else
    @vite($entries)
@endif
