<x-app-layout>
    <x-slot name="dashboardRole">konseli</x-slot>
    <x-slot name="headerTitle">Riwayat Konseling</x-slot>

    <section class="space-y-6">
        <x-dashboard.page-header title="Rekap Konseling"
            subtitle="Pantau status semua pengajuan konselingmu di sini." />

        @if (session('success'))
            <div class="kc-card border-emerald-100 bg-emerald-50 p-4 text-sm font-medium text-emerald-700">
                {{ session('success') }}</div>
        @endif
        @if (session('info'))
            <div class="kc-card border-indigo-100 bg-indigo-50 p-4 text-sm font-medium text-indigo-700">
                {{ session('info') }}</div>
        @endif

        {{-- Reschedule yang butuh konfirmasi konseli --}}
        @php $rescheduleList = $riwayat->where('status_pengajuan', 'reschedule'); @endphp
        @if ($rescheduleList->isNotEmpty())
            <div class="kc-card border-amber-100 bg-amber-50 p-5">
                <p class="text-sm font-bold text-amber-700">⚠ Konselor mengusulkan reschedule</p>
                <div class="mt-4 space-y-4">
                    @foreach ($rescheduleList as $p)
                        <div class="rounded-lg border border-amber-200 bg-white p-5">
                            <div class="flex flex-wrap items-start justify-between gap-4">
                                <div class="text-sm">
                                    <p class="font-semibold text-kc-heading">{{ $p->konselor?->user?->nama ?? '-' }}</p>
                                    <p class="mt-1 text-slate-500">
                                        Waktu awal:
                                        <span class="font-medium">
                                            {{ \Illuminate\Support\Carbon::parse($p->tanggal_usulan)->translatedFormat('d M Y') }},
                                            {{ \Illuminate\Support\Carbon::parse($p->jam_usulan)->format('H:i') }}
                                        </span>
                                    </p>
                                    <p class="mt-1 text-slate-500">
                                        Usulan baru:
                                        <span class="font-semibold text-amber-700">
                                            {{ \Illuminate\Support\Carbon::parse($p->tanggal_reschedule)->translatedFormat('d M Y') }},
                                            {{ \Illuminate\Support\Carbon::parse($p->jam_reschedule)->format('H:i') }}
                                        </span>
                                    </p>
                                    @if ($p->catatan_reschedule)
                                        <p class="mt-1 text-xs text-slate-400">Catatan: {{ $p->catatan_reschedule }}</p>
                                    @endif
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <form method="POST"
                                        action="{{ route('konseli.reschedule.konfirmasi', $p->id_pengajuan) }}">
                                        @csrf
                                        <input type="hidden" name="aksi" value="setuju">
                                        <button type="submit"
                                            class="rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-700">
                                            Setujui Reschedule
                                        </button>
                                    </form>
                                    <form method="POST"
                                        action="{{ route('konseli.reschedule.konfirmasi', $p->id_pengajuan) }}">
                                        @csrf
                                        <input type="hidden" name="aksi" value="tolak">
                                        <button type="submit"
                                            class="rounded-lg border border-red-200 px-4 py-2 text-xs font-semibold text-red-600 hover:bg-red-50">
                                            Tolak
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <article class="kc-card">
            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="text-lg font-semibold text-kc-heading">Semua Pengajuan</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[700px] text-left text-sm">
                    <thead class="text-xs uppercase tracking-wide text-slate-400">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Konselor</th>
                            <th class="px-6 py-4 font-semibold">Usulan Jadwal</th>
                            <th class="px-6 py-4 font-semibold">Jadwal Resmi</th>
                            <th class="px-6 py-4 font-semibold">Tipe</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                            <th class="px-6 py-4 font-semibold">Hasil</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($riwayat as $p)
                            <tr>
                                <td class="px-6 py-4 font-semibold text-kc-heading">
                                    {{ $p->konselor?->user?->nama ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-slate-500">
                                    @if ($p->tanggal_usulan)
                                        {{ \Illuminate\Support\Carbon::parse($p->tanggal_usulan)->translatedFormat('d M Y') }},
                                        {{ \Illuminate\Support\Carbon::parse($p->jam_usulan)->format('H:i') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-500">
                                    @if ($p->jadwal)
                                        {{ \Illuminate\Support\Carbon::parse($p->jadwal->tanggal)->translatedFormat('d M Y') }},
                                        {{ \Illuminate\Support\Carbon::parse($p->jadwal->jam)->format('H:i') }}
                                    @else
                                        <span class="text-slate-300">Belum dikonfirmasi</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-500">
                                    {{ ucfirst($p->tipe_konseling_usulan ?? '-') }}
                                </td>
                                <td class="px-6 py-4">
                                    <x-dashboard.status-badge :status="$p->status_pengajuan" />
                                    @if ($p->status_pengajuan === 'ditolak' && $p->alasan_penolakan)
                                        <p class="mt-1 text-xs text-slate-400">{{ $p->alasan_penolakan }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($p->status_pengajuan === 'selesai' && $p->hasil)
                                        <button type="button"
                                            onclick="document.getElementById('hasil-{{ $p->id_pengajuan }}').classList.toggle('hidden')"
                                            class="rounded-lg bg-sky-100 px-3 py-1.5 text-xs font-semibold text-sky-700 transition hover:bg-sky-200">
                                            Lihat Hasil
                                        </button>
                                    @elseif ($p->status_pengajuan === 'selesai')
                                        <span class="text-xs text-slate-300">-</span>
                                    @else
                                        <span class="text-xs text-slate-300">-</span>
                                    @endif
                                </td>
                            </tr>
                            {{-- Expandable hasil konseling --}}
                            @if ($p->status_pengajuan === 'selesai' && $p->hasil)
                                <tr id="hasil-{{ $p->id_pengajuan }}" class="hidden bg-sky-50">
                                    <td colspan="6" class="px-6 py-5">
                                        <p class="mb-1 text-xs font-semibold uppercase tracking-wide text-sky-600">
                                            Catatan Konseling</p>
                                        <p class="text-sm leading-6 text-slate-600">{{ $p->hasil->catatan_konseling }}
                                        </p>
                                        @if ($p->hasil->rekomendasi)
                                            <p
                                                class="mt-3 mb-1 text-xs font-semibold uppercase tracking-wide text-sky-600">
                                                Rekomendasi Tindak Lanjut</p>
                                            <p class="text-sm leading-6 text-slate-600">{{ $p->hasil->rekomendasi }}
                                            </p>
                                        @endif
                                        <p class="mt-3 text-xs text-slate-400">
                                            Dicatat pada
                                            {{ \Illuminate\Support\Carbon::parse($p->hasil->created_at)->translatedFormat('d M Y, H:i') }}
                                        </p>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                    Belum ada pengajuan.
                                    <a href="{{ route('konseli.pengajuan') }}"
                                        class="ml-1 font-semibold text-indigo-500 underline">Ajukan sekarang</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>
    </section>
</x-app-layout>
