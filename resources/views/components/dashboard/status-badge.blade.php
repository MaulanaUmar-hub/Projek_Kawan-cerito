@props(['status' => 'pending'])

@php
    $normalized = strtolower((string) $status);
    $classes = [
        'pending' => 'bg-amber-100 text-amber-700',
        'menunggu' => 'bg-amber-100 text-amber-700',
        'baru' => 'bg-amber-100 text-amber-700',
        'disetujui' => 'bg-emerald-100 text-emerald-700',
        'approved' => 'bg-emerald-100 text-emerald-700',
        'aktif' => 'bg-emerald-100 text-emerald-700',
        'berlangsung' => 'bg-indigo-100 text-indigo-700',
        'selesai' => 'bg-sky-100 text-sky-700',
        'completed' => 'bg-sky-100 text-sky-700',
        'ditolak' => 'bg-red-100 text-red-700',
        'rejected' => 'bg-red-100 text-red-700',
        'dibuat' => 'bg-slate-100 text-slate-600',
        'tersedia' => 'bg-emerald-100 text-emerald-700',
        'penuh' => 'bg-red-100 text-red-700',
    ];

    $labels = [
        'pending' => 'Menunggu',
        'menunggu' => 'Menunggu',
        'baru' => 'Menunggu',
        'disetujui' => 'Disetujui',
        'approved' => 'Disetujui',
        'aktif' => 'Aktif',
        'berlangsung' => 'Berlangsung',
        'selesai' => 'Selesai',
        'completed' => 'Selesai',
        'ditolak' => 'Ditolak',
        'rejected' => 'Ditolak',
        'dibuat' => 'Dibuat',
        'tersedia' => 'Tersedia',
        'penuh' => 'Penuh',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex rounded-md px-3 py-1 text-xs font-semibold ' . ($classes[$normalized] ?? 'bg-slate-100 text-slate-600')]) }}>
    {{ $labels[$normalized] ?? ucfirst($normalized) }}
</span>
