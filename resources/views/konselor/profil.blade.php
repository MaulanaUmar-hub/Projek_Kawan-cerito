<x-app-layout>
    <x-slot name="dashboardRole">konselor</x-slot>
    <x-slot name="headerTitle">Profil Konselor</x-slot>

    <section class="space-y-6">
        <x-dashboard.page-header
            title="Profil Konselor"
            subtitle="Informasi singkat akun konselor yang sedang digunakan pada dashboard Kawan Cerito."
        />

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-[360px_1fr]">
            <article class="kc-card p-6">
                <div class="flex items-center gap-4">
                    <div class="flex h-16 w-16 items-center justify-center rounded-xl bg-indigo-500 text-xl font-bold text-white">
                        {{ strtoupper(mb_substr($profile['nama'], 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-kc-heading">{{ $profile['nama'] }}</h2>
                        <p class="text-sm text-slate-400">{{ $profile['email'] }}</p>
                    </div>
                </div>

                <div class="mt-6">
                    <x-dashboard.status-badge :status="$profile['status']" />
                </div>
            </article>

            <x-dashboard.form-card title="Detail Profesional" description="Profil ini dapat dikembangkan nanti saat data konselor sudah dihubungkan ke database.">
                <dl class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-lg border border-slate-100 p-4">
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Nama</dt>
                        <dd class="mt-2 font-semibold text-kc-heading">{{ $profile['nama'] }}</dd>
                    </div>
                    <div class="rounded-lg border border-slate-100 p-4">
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Email</dt>
                        <dd class="mt-2 font-semibold text-kc-heading">{{ $profile['email'] }}</dd>
                    </div>
                    <div class="rounded-lg border border-slate-100 p-4 sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Spesialisasi</dt>
                        <dd class="mt-2 font-semibold text-kc-heading">{{ $profile['spesialisasi'] }}</dd>
                    </div>
                </dl>
            </x-dashboard.form-card>
        </div>
    </section>
</x-app-layout>
