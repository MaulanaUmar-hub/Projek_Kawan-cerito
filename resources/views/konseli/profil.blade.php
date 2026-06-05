<x-app-layout>
    <x-slot name="dashboardRole">konseli</x-slot>
    <x-slot name="headerTitle">Profil Saya</x-slot>

    <section class="space-y-6">
        <x-dashboard.page-header
            title="Profil Saya"
            subtitle="Kelola informasi dasar akun konseli kamu dengan tenang dan aman."
        />

        <x-dashboard.form-card
            title="Informasi Profil"
            description="Halaman ini masih berfokus pada tampilan frontend. Integrasi simpan data bisa disambungkan ke profil konseli."
        >
            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-kc-heading">Nama</label>
                    <input value="{{ $profile->nama }}" class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm" readonly>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-kc-heading">Email</label>
                    <input value="{{ $profile->email }}" class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm" readonly>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-kc-heading">Asal</label>
                    <input value="{{ $profile->asal }}" class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm" readonly>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-kc-heading">Nomor HP</label>
                    <input value="{{ $profile->no_hp }}" class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm" readonly>
                </div>
            </div>
        </x-dashboard.form-card>
    </section>
</x-app-layout>
