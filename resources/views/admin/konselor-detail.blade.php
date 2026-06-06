<x-app-layout>
    <x-slot name="dashboardRole">admin</x-slot>
    <x-slot name="headerTitle">Detail Konselor</x-slot>

    @php
        $konselor = $konselorAktif->first() ?? $konselorPending->first();
        $formatDate = fn ($value) => \Illuminate\Support\Carbon::parse($value)->translatedFormat('d M Y');
    @endphp

    <section class="space-y-6">
        <x-dashboard.page-header
            title="Detail Konselor"
            subtitle="Informasi ringkas konselor untuk kebutuhan review admin."
        />

        <div class="grid gap-6 lg:grid-cols-[360px_1fr]">
            <article class="kc-card p-6">
                <div class="grid h-16 w-16 place-items-center rounded-xl bg-indigo-500 text-xl font-bold text-white">
                    {{ strtoupper(mb_substr($konselor['nama'], 0, 1)) }}
                </div>
                <h2 class="mt-5 text-xl font-semibold text-kc-heading">{{ $konselor['nama'] }}</h2>
                <p class="mt-2 text-sm text-slate-500">{{ $konselor['email'] }}</p>
                <div class="mt-5"><x-dashboard.status-badge :status="$konselor['status']" /></div>
            </article>

            <article class="kc-card p-6">
                <h2 class="text-lg font-semibold text-kc-heading">Informasi Pengajuan</h2>
                <dl class="mt-5 grid gap-4 md:grid-cols-2">
                    <div class="rounded-lg border border-slate-100 p-4">
                        <dt class="text-xs font-semibold uppercase text-slate-400">Asal</dt>
                        <dd class="mt-2 font-semibold text-kc-heading">{{ $konselor['asal'] }}</dd>
                    </div>
                    <div class="rounded-lg border border-slate-100 p-4">
                        <dt class="text-xs font-semibold uppercase text-slate-400">No HP</dt>
                        <dd class="mt-2 font-semibold text-kc-heading">{{ $konselor['no_hp'] }}</dd>
                    </div>
                    <div class="rounded-lg border border-slate-100 p-4">
                        <dt class="text-xs font-semibold uppercase text-slate-400">Spesialisasi</dt>
                        <dd class="mt-2 font-semibold text-kc-heading">{{ $konselor['spesialisasi'] }}</dd>
                    </div>
                    <div class="rounded-lg border border-slate-100 p-4">
                        <dt class="text-xs font-semibold uppercase text-slate-400">Tanggal Pengajuan</dt>
                        <dd class="mt-2 font-semibold text-kc-heading">{{ $formatDate($konselor['tanggal']) }}</dd>
                    </div>
                </dl>
            </article>
        </div>
    </section>
</x-app-layout>
