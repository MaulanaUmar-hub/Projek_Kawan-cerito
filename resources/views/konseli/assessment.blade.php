<x-app-layout>
    <x-slot name="dashboardRole">konseli</x-slot>
    <x-slot name="headerTitle">Assessment Awal</x-slot>

    <section class="space-y-6">
        <x-dashboard.page-header
            title="Assessment Awal"
            subtitle="Ceritakan kondisi awalmu agar konselor dapat memahami kebutuhanmu."
        />

        @if (session('success'))
            <div class="kc-card border-emerald-100 bg-emerald-50 p-4 text-sm font-medium text-emerald-700">
                {{ session('success') }}
                <a href="{{ route('konseli.pengajuan') }}" class="ml-2 font-semibold text-emerald-800 underline">Ajukan konseling</a>
            </div>
        @endif

        <div class="grid gap-6 xl:grid-cols-[1fr_360px]">
            <x-dashboard.form-card
                title="Form Assessment"
                description="Isi dengan bahasa yang nyaman. Informasi ini membantu konselor memahami kondisi awalmu."
            >
                <form method="POST" action="{{ route('konseli.assessment.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="keluhan" class="mb-2 block text-sm font-semibold text-kc-heading">Keluhan / Permasalahan</label>
                        <textarea id="keluhan" name="keluhan" rows="5" class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100" placeholder="Contoh: Akhir-akhir ini saya mudah cemas, sulit tidur, dan sulit fokus."></textarea>
                        <p class="mt-2 text-xs text-slate-400">Cukup ceritakan garis besarnya. Kamu tetap bisa menjelaskan lebih detail saat sesi.</p>
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label for="urgensi" class="mb-2 block text-sm font-semibold text-kc-heading">Tingkat Urgensi</label>
                            <select id="urgensi" name="urgensi" class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100">
                                <option value="">Pilih tingkat urgensi</option>
                                <option>Rendah</option>
                                <option>Sedang</option>
                                <option>Tinggi</option>
                            </select>
                        </div>

                        <div>
                            <label for="jenis_konseling" class="mb-2 block text-sm font-semibold text-kc-heading">Preferensi Jenis Konseling</label>
                            <select id="jenis_konseling" name="jenis_konseling" class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100">
                                <option value="">Pilih preferensi</option>
                                <option>Chat</option>
                                <option>Video Call</option>
                                <option>WhatsApp</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="catatan" class="mb-2 block text-sm font-semibold text-kc-heading">Catatan Tambahan</label>
                        <textarea id="catatan" name="catatan" rows="4" class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100" placeholder="Tambahkan hal lain yang ingin konselor ketahui."></textarea>
                    </div>

                    <div class="flex flex-wrap gap-3 pt-2">
                        <button type="submit" class="rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700">
                            Simpan Assessment
                        </button>
                        <a href="{{ route('konseli.dashboard') }}" class="rounded-lg border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                            Batal / Kembali
                        </a>
                    </div>
                </form>
            </x-dashboard.form-card>

            <aside class="space-y-6">
                <article class="kc-card p-6">
                    <h2 class="text-lg font-semibold text-kc-heading">Assessment Terakhir</h2>
                    <div class="mt-5 space-y-4 text-sm">
                        <div>
                            <p class="text-slate-400">Tanggal</p>
                            <p class="mt-1 font-semibold text-kc-heading">{{ \Illuminate\Support\Carbon::parse($lastAssessment->tanggal)->translatedFormat('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Keluhan</p>
                            <p class="mt-1 leading-6">{{ $lastAssessment->keluhan }}</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <span class="rounded-md bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">{{ $lastAssessment->urgensi }}</span>
                            <span class="rounded-md bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700">{{ $lastAssessment->jenis_konseling }}</span>
                        </div>
                    </div>
                </article>

                <article class="kc-card p-6">
                    <h2 class="text-lg font-semibold text-kc-heading">Langkah Berikutnya</h2>
                    <ol class="mt-5 space-y-4 text-sm text-slate-500">
                        <li class="flex gap-3"><span class="font-bold text-indigo-500">1</span> Simpan assessment awal.</li>
                        <li class="flex gap-3"><span class="font-bold text-indigo-500">2</span> Ajukan konseling dengan memilih konselor dan jadwal.</li>
                        <li class="flex gap-3"><span class="font-bold text-indigo-500">3</span> Pantau status pengajuan di riwayat konseling.</li>
                    </ol>
                </article>
            </aside>
        </div>
    </section>
</x-app-layout>
