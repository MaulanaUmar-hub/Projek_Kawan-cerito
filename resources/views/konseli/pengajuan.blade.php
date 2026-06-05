<x-app-layout>
    <x-slot name="dashboardRole">konseli</x-slot>
    <x-slot name="headerTitle">Ajukan Konseling</x-slot>

    <section class="space-y-6">
        <x-dashboard.page-header
            title="Ajukan Konseling"
            subtitle="Pilih konselor dan jadwal yang sesuai dengan kebutuhanmu."
        />

        @if (session('success'))
            <div class="kc-card border-emerald-100 bg-emerald-50 p-4 text-sm font-medium text-emerald-700">
                {{ session('success') }}
                <a href="{{ route('konseli.riwayat') }}" class="ml-2 font-semibold text-emerald-800 underline">Lihat riwayat</a>
            </div>
        @endif

        <article class="kc-card p-5">
            <div class="grid gap-3 md:grid-cols-4">
                @foreach ([['1', 'Assessment'], ['2', 'Pilih Konselor'], ['3', 'Pilih Jadwal'], ['4', 'Kirim Pengajuan']] as [$number, $label])
                    <div class="flex items-center gap-3 rounded-lg bg-indigo-50 px-4 py-3">
                        <span class="grid h-8 w-8 place-items-center rounded-lg bg-indigo-600 text-sm font-bold text-white">{{ $number }}</span>
                        <span class="text-sm font-semibold text-indigo-700">{{ $label }}</span>
                    </div>
                @endforeach
            </div>
        </article>

        <div class="grid gap-6 xl:grid-cols-[1fr_380px]">
            <x-dashboard.form-card
                title="Form Pengajuan"
                description="Pastikan assessment, konselor, dan jadwal sudah sesuai sebelum mengirim pengajuan."
            >
                <form method="POST" action="{{ route('konseli.pengajuan.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="assessment" class="mb-2 block text-sm font-semibold text-kc-heading">Pilih Assessment</label>
                        <select id="assessment" name="assessment" class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100">
                            <option value="">Pilih assessment yang ingin digunakan</option>
                            @foreach ($assessments as $assessment)
                                <option value="{{ $assessment['id'] }}">{{ $assessment['label'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label for="konselor" class="mb-2 block text-sm font-semibold text-kc-heading">Pilih Konselor</label>
                            <select id="konselor" name="konselor" class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100">
                                <option value="">Pilih konselor</option>
                                @foreach ($konselors as $konselor)
                                    <option value="{{ $konselor['id'] }}">{{ $konselor['nama'] }} - {{ $konselor['spesialisasi'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="jadwal" class="mb-2 block text-sm font-semibold text-kc-heading">Pilih Jadwal</label>
                            <select id="jadwal" name="jadwal" class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100">
                                <option value="">Pilih jadwal</option>
                                @foreach ($jadwals as $jadwal)
                                    <option value="{{ $jadwal['id'] }}">{{ $jadwal['label'] }} - {{ ucfirst($jadwal['status']) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label for="jenis_layanan" class="mb-2 block text-sm font-semibold text-kc-heading">Jenis Layanan</label>
                            <select id="jenis_layanan" name="jenis_layanan" class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100">
                                <option value="">Pilih jenis layanan</option>
                                <option>Chat Konseling</option>
                                <option>Video Konseling</option>
                                <option>WhatsApp Konseling</option>
                            </select>
                        </div>

                        <div>
                            <label for="topik" class="mb-2 block text-sm font-semibold text-kc-heading">Topik/Keluhan Singkat</label>
                            <input id="topik" name="topik" type="text" class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100" placeholder="Contoh: kecemasan, stres, relasi">
                        </div>
                    </div>

                    <div>
                        <label for="catatan" class="mb-2 block text-sm font-semibold text-kc-heading">Catatan Tambahan</label>
                        <textarea id="catatan" name="catatan" rows="4" class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100" placeholder="Tulis preferensi atau informasi tambahan bila ada."></textarea>
                    </div>

                    <div class="flex flex-wrap gap-3 pt-2">
                        <button type="submit" class="rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700">
                            Kirim Pengajuan
                        </button>
                        <a href="{{ route('konseli.dashboard') }}" class="rounded-lg border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                            Kembali
                        </a>
                    </div>
                </form>
            </x-dashboard.form-card>

            <aside class="space-y-6">
                <article class="kc-card p-6">
                    <h2 class="text-lg font-semibold text-kc-heading">Ringkasan Pengajuan</h2>
                    <div class="mt-5 space-y-4 text-sm">
                        <div class="rounded-lg bg-indigo-50 p-4">
                            <p class="font-semibold text-indigo-700">Status awal</p>
                            <p class="mt-1 text-slate-500">Pengajuan akan masuk sebagai menunggu persetujuan konselor.</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Estimasi proses</p>
                            <p class="mt-1 font-semibold text-kc-heading">1 x 24 jam</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Langkah setelah kirim</p>
                            <p class="mt-1 leading-6">Pantau status pengajuan di riwayat konseling dan ikuti instruksi jadwal yang disetujui.</p>
                        </div>
                    </div>
                </article>

                <article class="kc-card p-6">
                    <h2 class="text-lg font-semibold text-kc-heading">Ketersediaan Jadwal</h2>
                    <div class="mt-5 space-y-3">
                        @foreach ($jadwals as $jadwal)
                            <div class="flex items-center justify-between gap-4 rounded-lg border border-slate-100 p-4">
                                <span class="text-sm font-semibold text-kc-heading">{{ $jadwal['label'] }}</span>
                                <x-dashboard.status-badge :status="$jadwal['status']" />
                            </div>
                        @endforeach
                    </div>
                </article>

                <article class="kc-card p-6">
                    <h2 class="text-lg font-semibold text-kc-heading">Konselor Tersedia</h2>
                    <div class="mt-5 space-y-4">
                        @foreach ($konselors as $konselor)
                            <div class="rounded-lg border border-slate-100 p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-kc-heading">{{ $konselor['nama'] }}</p>
                                        <p class="mt-1 text-sm text-slate-400">{{ $konselor['spesialisasi'] }}</p>
                                    </div>
                                    <x-dashboard.status-badge :status="$konselor['status']" />
                                </div>
                            </div>
                        @endforeach
                    </div>
                </article>
            </aside>
        </div>
    </section>
</x-app-layout>
