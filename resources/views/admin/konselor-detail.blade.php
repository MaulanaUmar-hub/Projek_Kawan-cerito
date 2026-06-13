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
    @endphp

    <section class="space-y-6">
        <x-dashboard.page-header
            title="Detail Konselor"
            subtitle="Informasi ringkas konselor untuk kebutuhan review admin."
        />

        <div class="grid gap-6 lg:grid-cols-[360px_1fr]">
            <article class="kc-card p-6">
                <div class="grid h-16 w-16 place-items-center rounded-xl bg-indigo-500 text-xl font-bold text-white">
                    {{ strtoupper(mb_substr($nama, 0, 1)) }}
                </div>
                <h2 class="mt-5 text-xl font-semibold text-kc-heading">{{ $nama }}</h2>
                <p class="mt-2 text-sm text-slate-500">{{ $email }}</p>
                <div class="mt-5"><x-dashboard.status-badge :status="$status" /></div>
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
