<x-app-layout>
    <x-slot name="dashboardRole">admin</x-slot>
    <x-slot name="headerTitle">Detail Konselor</x-slot>

    @php
        $formatDate = fn ($value) => \Illuminate\Support\Carbon::parse($value)->translatedFormat('d M Y');
        $nama = $konselor->user->nama ?? '-';
        $email = $konselor->user->email ?? '-';
        $spesialisasi = $konselor->spesialisasi ?? '-';
        $peminatan = $konselor->peminatan ?? '-';
        $noHp = $konselor->no_hp ?? '-';
        $status = $konselor->status ?? 'pending';
        $ringkasanSesi = [
            [
                'label' => 'Total Sesi',
                'value' => $statistikSesi['total'] ?? 0,
                'caption' => 'Seluruh pengajuan yang ditangani',
                'icon' => 'bi bi-calendar2-check-fill',
                'class' => 'bg-indigo-50 text-indigo-600',
            ],
            [
                'label' => 'Sedang Berjalan',
                'value' => $statistikSesi['berjalan'] ?? 0,
                'caption' => 'Konseling aktif/disetujui',
                'icon' => 'bi bi-play-circle-fill',
                'class' => 'bg-emerald-50 text-emerald-600',
            ],
            [
                'label' => 'Selesai',
                'value' => $statistikSesi['selesai'] ?? 0,
                'caption' => 'Sesi yang telah selesai',
                'icon' => 'bi bi-check-circle-fill',
                'class' => 'bg-sky-50 text-sky-600',
            ],
        ];
    @endphp

    <section class="space-y-6">
        <x-dashboard.page-header
            title="Detail Konselor"
            subtitle="Informasi ringkas konselor untuk kebutuhan review admin."
        />

        <div class="grid gap-4 md:grid-cols-3">
            @foreach ($ringkasanSesi as $item)
                <article class="kc-card p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-slate-400">{{ $item['label'] }}</p>
                            <p class="mt-2 text-3xl font-bold text-kc-heading">{{ $item['value'] }}</p>
                            <p class="mt-1 text-xs text-slate-400">{{ $item['caption'] }}</p>
                        </div>
                        <div class="grid h-11 w-11 place-items-center rounded-xl {{ $item['class'] }}">
                            <i class="{{ $item['icon'] }}"></i>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="grid gap-6 lg:grid-cols-[360px_1fr]">
            <article class="kc-card p-6">
                <div class="grid h-20 w-20 place-items-center rounded-2xl bg-gradient-to-br from-indigo-500 to-cyan-400 text-2xl font-bold text-white shadow-lg shadow-indigo-100">
                    {{ strtoupper(mb_substr($nama, 0, 1)) }}
                </div>
                <h2 class="mt-5 text-xl font-semibold text-kc-heading">{{ $nama }}</h2>
                <p class="mt-2 text-sm text-slate-500">{{ $email }}</p>
                <div class="mt-5"><x-dashboard.status-badge :status="$status" /></div>
                <a
                    href="{{ route('admin.konselor.index') }}"
                    class="mt-6 inline-flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-600"
                >
                    <i class="bi bi-arrow-left"></i>
                    Kembali
                </a>
            </article>

            <article class="kc-card p-6">
                <h2 class="text-lg font-semibold text-kc-heading">Informasi Pengajuan</h2>
                <dl class="mt-5 grid gap-4 md:grid-cols-2">
                    <div class="rounded-lg border border-slate-100 p-4">
                        <dt class="text-xs font-semibold uppercase text-slate-400">Peminatan</dt>
                        <dd class="mt-2 font-semibold text-kc-heading">{{ $peminatan }}</dd>
                    </div>
                    <div class="rounded-lg border border-slate-100 p-4">
                        <dt class="text-xs font-semibold uppercase text-slate-400">No HP</dt>
                        <dd class="mt-2 font-semibold text-kc-heading">{{ $noHp }}</dd>
                    </div>
                    <div class="rounded-lg border border-slate-100 p-4">
                        <dt class="text-xs font-semibold uppercase text-slate-400">Spesialisasi</dt>
                        <dd class="mt-2 font-semibold text-kc-heading">{{ $spesialisasi }}</dd>
                    </div>
                    <div class="rounded-lg border border-slate-100 p-4">
                        <dt class="text-xs font-semibold uppercase text-slate-400">Tanggal Pengajuan</dt>
                        <dd class="mt-2 font-semibold text-kc-heading">{{ $formatDate($konselor->created_at) }}</dd>
                    </div>
                </dl>
            </article>
        </div>
    </section>
</x-app-layout>
