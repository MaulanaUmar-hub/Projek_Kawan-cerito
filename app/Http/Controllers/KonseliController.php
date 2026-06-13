<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Jadwal;
use App\Models\Konseli;
use App\Models\Konselor;
use App\Models\PengajuanKonseling;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class KonseliController extends Controller
{
    private function getKonseli(): ?Konseli
    {
        return Konseli::where('id_user', auth()->user()->id_user)->first();
    }

    // ─── Profile Setup ────────────────────────────────────────────────────────

    public function setupProfile()
    {
        return view('konseli.profile.setup', ['user' => auth()->user()]);
    }

    public function storeProfileSetup(Request $request)
    {
        $validated = $request->validate([
            'nama'   => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:L,P,N'],
            'asal'   => ['required', 'string', 'max:255'],
            'no_hp'  => ['required', 'string', 'max:30'],
            'foto'   => ['nullable', 'image', 'max:2048'],
        ]);

        $user = auth()->user();
        $user->update(['nama' => $validated['nama']]);

        $konseliData = [
            'asal'   => $validated['asal'],
            'no_hp'  => $validated['no_hp'],
            'gender' => $validated['gender'],
        ];

        if ($request->hasFile('foto')) {
            $konseli = $this->getKonseli();
            if ($konseli?->foto) {
                Storage::disk('public')->delete($konseli->foto);
            }
            $konseliData['foto'] = $request->file('foto')->store('konseli/profil', 'public');
        }

        Konseli::updateOrCreate(['id_user' => $user->id_user], $konseliData);

        return redirect()->route('konseli.dashboard')
            ->with('success', 'Profil berhasil dilengkapi. Selamat datang di Kawan Cerito!');
    }

    // ─── Dashboard ────────────────────────────────────────────────────────────

    public function index()
    {
        $user    = auth()->user();
        $konseli = $this->getKonseli();

        $pengajuan = PengajuanKonseling::where('id_konseli', $konseli->id_konseli)
            ->with(['konselor.user', 'jadwal'])
            ->latest('created_at')
            ->get();

        $assessmentTerakhir = Assessment::where('id_konseli', $konseli->id_konseli)
            ->latest('created_at')->first();

        // Jadwal berikutnya = pengajuan disetujui yang jadwalnya belum lewat
        $jadwalBerikutnya = PengajuanKonseling::where('id_konseli', $konseli->id_konseli)
            ->where('status_pengajuan', 'disetujui')
            ->whereHas('jadwal', fn($q) => $q->whereDate('tanggal', '>=', now()->toDateString()))
            ->with(['jadwal', 'konselor.user'])
            ->latest('created_at')
            ->first();

        $aktivitas = \App\Models\ActivityLog::where('id_user', $user->id_user)
            ->latest('created_at')->limit(5)->get();

        $statusCounts = [
            'pending'    => $pengajuan->where('status_pengajuan', 'menunggu')->count(),
            'reschedule' => $pengajuan->where('status_pengajuan', 'reschedule')->count(),
            'disetujui'  => $pengajuan->where('status_pengajuan', 'disetujui')->count(),
            'selesai'    => $pengajuan->where('status_pengajuan', 'selesai')->count(),
        ];

        return view('konseli.dashboard', [
            'stats' => [
                ['label' => 'Total Konseling',      'value' => $pengajuan->count(),             'icon' => 'bx-conversation', 'color' => 'primary'],
                ['label' => 'Menunggu / Reschedule', 'value' => $statusCounts['pending'] + $statusCounts['reschedule'], 'icon' => 'bx-time-five', 'color' => 'warning'],
                ['label' => 'Konseling Disetujui',  'value' => $statusCounts['disetujui'],       'icon' => 'bx-check-circle', 'color' => 'success'],
                ['label' => 'Konseling Selesai',    'value' => $statusCounts['selesai'],         'icon' => 'bx-badge-check',  'color' => 'info'],
            ],
            'statusCounts'       => $statusCounts,
            'riwayatTerbaru'     => $pengajuan->take(5),
            'assessmentTerakhir' => $assessmentTerakhir,
            'jadwalBerikutnya'   => $jadwalBerikutnya,
            'aktivitas'          => $aktivitas,
            'isFallback'         => false,
        ]);
    }

    // ─── Assessment ───────────────────────────────────────────────────────────

    public function assessment()
    {
        $konseli        = $this->getKonseli();
        $lastAssessment = Assessment::where('id_konseli', $konseli->id_konseli)
            ->latest('created_at')->first();

        return view('konseli.assessment', compact('lastAssessment', 'konseli'));
    }

    public function storeAssessment(Request $request)
    {
        $validated = $request->validate([
            'keluhan' => ['required', 'string', 'max:2000'],
            'no_hp'   => ['required', 'string', 'max:20', 'regex:/^(\\+62|08)[0-9]{7,13}$/'],
        ], [
            'no_hp.required' => 'Nomor WhatsApp wajib diisi agar konselor dapat menghubungimu.',
            'no_hp.regex'    => 'Format nomor tidak valid. Gunakan format 08xxx atau +62xxx.',
        ]);

        $konseli = $this->getKonseli();

        // Simpan/update no_hp di profil konseli
        $konseli->update(['no_hp' => $validated['no_hp']]);

        Assessment::create(['id_konseli' => $konseli->id_konseli, 'keluhan' => $validated['keluhan']]);

        return back()->with('success', 'Assessment berhasil disimpan. Kamu dapat melanjutkan ke pengajuan konseling.');
    }

    // ─── Pengajuan ────────────────────────────────────────────────────────────

    public function pengajuan()
    {
        $konseli = $this->getKonseli();

        $assessments = Assessment::where('id_konseli', $konseli->id_konseli)
            ->latest('created_at')->get()
            ->map(fn($a) => [
                'id'    => $a->id_assessment,
                'label' => 'Assessment ' . Carbon::parse($a->created_at)->translatedFormat('d M Y')
                    . ' — ' . \Illuminate\Support\Str::limit($a->keluhan, 50),
            ]);

        $konselors = Konselor::with('user')->where('status', 'aktif')->get()
            ->map(fn($k) => [
                'id'           => $k->id_konselor,
                'nama'         => $k->user->nama,
                'spesialisasi' => $k->spesialisasi ?? '-',
                'peminatan'    => $k->peminatan ?? '',
                'catatan'      => $k->catatan_profil ?? '',
                'no_hp'        => $k->no_hp ?? '-',
                'whatsapp'     => $k->link_whatsapp ?? '',
                'foto_url'     => $k->foto ? Storage::disk('public')->url($k->foto) : null,
            ]);

        return view('konseli.pengajuan', compact('assessments', 'konselors'));
    }

    public function storePengajuan(Request $request)
    {
        $validated = $request->validate([
            'id_assessment'        => ['required', 'exists:assessments,id_assessment'],
            'id_konselor'          => ['required', 'exists:konselor,id_konselor'],
            'tanggal_usulan'       => ['required', 'date', 'after_or_equal:today'],
            'jam_usulan'           => ['required', 'date_format:H:i'],
            'tipe_konseling_usulan' => ['required', 'in:online,offline'],
        ], [
            'id_assessment.required'         => 'Pilih assessment yang akan digunakan.',
            'id_konselor.required'           => 'Pilih konselor terlebih dahulu.',
            'tanggal_usulan.required'        => 'Masukkan tanggal yang kamu usulkan.',
            'tanggal_usulan.after_or_equal'  => 'Tanggal usulan tidak boleh di masa lalu.',
            'jam_usulan.required'            => 'Masukkan jam yang kamu usulkan.',
            'tipe_konseling_usulan.required' => 'Pilih tipe konseling.',
        ]);

        $konseli = $this->getKonseli();

        // Pastikan assessment milik konseli ini
        $assessment = Assessment::findOrFail($validated['id_assessment']);
        if ($assessment->id_konseli !== $konseli->id_konseli) {
            return back()->withInput()->withErrors(['id_assessment' => 'Assessment tidak valid.']);
        }

        PengajuanKonseling::create([
            'id_konseli'            => $konseli->id_konseli,
            'id_konselor'           => $validated['id_konselor'],
            'id_assessment'         => $validated['id_assessment'],
            'id_jadwal'             => null,
            'status_pengajuan'      => 'menunggu',
            'tanggal_usulan'        => $validated['tanggal_usulan'],
            'jam_usulan'            => $validated['jam_usulan'],
            'tipe_konseling_usulan' => $validated['tipe_konseling_usulan'],
        ]);

        return redirect()->route('konseli.riwayat')
            ->with('success', 'Pengajuan konseling berhasil dikirim. Konselor akan meninjau jadwal usulanmu.');
    }

    // ─── Konfirmasi Reschedule dari Konselor ─────────────────────────────────

    public function konfirmasiReschedule(Request $request, $id)
    {
        $konseli   = $this->getKonseli();
        $pengajuan = PengajuanKonseling::where('id_pengajuan', $id)
            ->where('id_konseli', $konseli->id_konseli)
            ->where('status_pengajuan', 'reschedule')
            ->firstOrFail();

        $aksi = $request->input('aksi'); // 'setuju' | 'tolak'

        if ($aksi === 'setuju') {
            // Buat Jadwal berdasarkan waktu reschedule konselor
            $jadwal = Jadwal::create([
                'id_konselor'    => $pengajuan->id_konselor,
                'tanggal'        => $pengajuan->tanggal_reschedule,
                'jam'            => $pengajuan->jam_reschedule,
                'status_jadwal'  => 'terpakai',
                'tipe_konseling' => $pengajuan->tipe_konseling_usulan,
            ]);

            $pengajuan->update([
                'status_pengajuan' => 'disetujui',
                'id_jadwal'        => $jadwal->id_jadwal,
            ]);

            return back()->with('success', 'Jadwal reschedule disetujui. Konseling terkonfirmasi.');
        }

        // Tolak reschedule → kembali ke menunggu agar bisa negosiasi lagi
        $pengajuan->update([
            'status_pengajuan'  => 'menunggu',
            'tanggal_reschedule' => null,
            'jam_reschedule'    => null,
            'catatan_reschedule' => null,
        ]);

        return back()->with('info', 'Reschedule ditolak. Pengajuan dikembalikan ke status menunggu. Konselor akan meninjau kembali.');
    }

    // ─── Riwayat ──────────────────────────────────────────────────────────────

    public function riwayat()
    {
        $konseli = $this->getKonseli();

        $riwayat = PengajuanKonseling::where('id_konseli', $konseli->id_konseli)
            ->with(['konselor.user', 'jadwal', 'hasil'])
            ->latest('created_at')
            ->get();

        return view('konseli.riwayat', compact('riwayat'));
    }

    // ─── Jadwal ───────────────────────────────────────────────────────────────

    public function jadwal()
    {
        $konseli = $this->getKonseli();

        $jadwals = PengajuanKonseling::where('id_konseli', $konseli->id_konseli)
            ->with(['konselor.user', 'jadwal'])
            ->whereIn('status_pengajuan', ['disetujui', 'selesai', 'menunggu', 'reschedule'])
            ->latest('created_at')
            ->get();

        return view('konseli.jadwal', compact('jadwals'));
    }

    // ─── Profil ───────────────────────────────────────────────────────────────

    public function profil()
    {
        $user    = auth()->user();
        $konseli = $this->getKonseli();

        return view('konseli.profil', [
            'profile' => (object) [
                'nama'    => $user->nama,
                'email'   => $user->email,
                'asal'    => $konseli?->asal ?? '-',
                'no_hp'   => $konseli?->no_hp ?? '-',
                'gender'  => $konseli?->gender ?? '-',
                'foto_url' => $konseli?->foto
                    ? Storage::disk('public')->url($konseli->foto)
                    : null,
            ],
        ]);
    }
}
