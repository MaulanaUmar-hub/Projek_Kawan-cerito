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
    // ─── Helper ───────────────────────────────────────────────────────────────

    /**
     * Ambil record Konseli milik user yang sedang login.
     */
    private function getKonseli(): ?Konseli
    {
        return Konseli::where('id_user', auth()->user()->id_user)->first();
    }

    // ─── Profile Setup ────────────────────────────────────────────────────────

    public function setupProfile()
    {
        return view('konseli.profile.setup', [
            'user' => auth()->user(),
        ]);
    }

    public function storeProfileSetup(Request $request)
    {
        $validated = $request->validate([
            'nama'   => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:L,P,N'],
            'asal'   => ['required', 'string', 'max:255'],
            'no_hp'  => ['required', 'string', 'max:30'],
            'foto'   => ['nullable', 'image', 'max:2048'],
        ], [
            'nama.required'   => 'Nama lengkap wajib diisi.',
            'gender.required' => 'Pilih gender yang paling sesuai.',
            'gender.in'       => 'Pilihan gender tidak valid.',
            'asal.required'   => 'Asal wajib diisi.',
            'no_hp.required'  => 'Nomor HP wajib diisi.',
            'foto.image'      => 'File foto harus berupa gambar.',
            'foto.max'        => 'Ukuran foto maksimal 2 MB.',
        ]);

        $user = auth()->user();

        // Update nama di tabel users (boleh diubah dari nama samaran)
        $user->update(['nama' => $validated['nama']]);

        // Update/buat record konseli dengan data profil yang dibutuhkan
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

        Konseli::updateOrCreate(
            ['id_user' => $user->id_user],
            $konseliData
        );

        return redirect()
            ->route('konseli.dashboard')
            ->with('success', 'Profil berhasil dilengkapi. Selamat datang di Kawan Cerito!');
    }

    // ─── Dashboard ────────────────────────────────────────────────────────────

    public function index()
    {
        $user    = auth()->user();
        $konseli = $this->getKonseli();

        $pengajuan = PengajuanKonseling::where('id_konseli', $konseli->id_konseli)
            ->with(['konselor.user'])
            ->latest('created_at')
            ->get();

        $assessmentTerakhir = Assessment::where('id_konseli', $konseli->id_konseli)
            ->latest('created_at')
            ->first();

        $jadwalBerikutnya = PengajuanKonseling::where('id_konseli', $konseli->id_konseli)
            ->whereHas('jadwal', fn($q) => $q->whereDate('tanggal', '>=', now()->toDateString()))
            ->with(['jadwal', 'konselor.user'])
            ->latest('created_at')
            ->first();

        $aktivitas = \App\Models\ActivityLog::where('id_user', $user->id_user)
            ->latest('created_at')
            ->limit(5)
            ->get();

        $statusCounts = [
            'pending'   => $pengajuan->where('status_pengajuan', 'menunggu')->count(),
            'disetujui' => $pengajuan->where('status_pengajuan', 'disetujui')->count(),
            'selesai'   => $pengajuan->where('status_pengajuan', 'selesai')->count(),
        ];

        return view('konseli.dashboard', [
            'stats' => [
                ['label' => 'Total Konseling',      'value' => $pengajuan->count(),            'icon' => 'bx-conversation', 'color' => 'primary'],
                ['label' => 'Pengajuan Menunggu',   'value' => $statusCounts['pending'],        'icon' => 'bx-time-five',   'color' => 'warning'],
                ['label' => 'Konseling Disetujui',  'value' => $statusCounts['disetujui'],      'icon' => 'bx-check-circle', 'color' => 'success'],
                ['label' => 'Konseling Selesai',    'value' => $statusCounts['selesai'],        'icon' => 'bx-badge-check', 'color' => 'info'],
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
        $konseli = $this->getKonseli();

        $lastAssessment = Assessment::where('id_konseli', $konseli->id_konseli)
            ->latest('created_at')
            ->first();

        return view('konseli.assessment', [
            'lastAssessment' => $lastAssessment,
        ]);
    }

    public function storeAssessment(Request $request)
    {
        $validated = $request->validate([
            'keluhan' => ['required', 'string', 'max:2000'],
        ], [
            'keluhan.required' => 'Ceritakan keluhanmu terlebih dahulu.',
        ]);

        $konseli = $this->getKonseli();

        Assessment::create([
            'id_konseli' => $konseli->id_konseli,
            'keluhan'    => $validated['keluhan'],
        ]);

        return back()->with('success', 'Assessment berhasil disimpan. Kamu dapat melanjutkan ke pengajuan konseling.');
    }

    // ─── Pengajuan ────────────────────────────────────────────────────────────

    public function pengajuan()
    {
        $konseli = $this->getKonseli();

        $assessments = Assessment::where('id_konseli', $konseli->id_konseli)
            ->latest('created_at')
            ->get()
            ->map(fn($a) => [
                'id'    => $a->id_assessment,
                'label' => 'Assessment ' . Carbon::parse($a->created_at)->translatedFormat('d M Y') . ' — ' . \Illuminate\Support\Str::limit($a->keluhan, 40),
            ]);

        $konselors = Konselor::with('user')
            ->where('status', 'aktif')
            ->get()
            ->map(fn($k) => [
                'id'           => $k->id_konselor,
                'nama'         => $k->user->nama,
                'spesialisasi' => $k->spesialisasi ?? '-',
                'status'       => 'tersedia',
            ]);

        $jadwals = Jadwal::where('status_jadwal', 'tersedia')
            ->whereDate('tanggal', '>=', now()->toDateString())
            ->orderBy('tanggal')
            ->orderBy('jam')
            ->get()
            ->map(fn($j) => [
                'id'    => $j->id_jadwal,
                'label' => Carbon::parse($j->tanggal)->translatedFormat('d M Y') . ', ' . Carbon::parse($j->jam)->format('H:i'),
                'tipe'  => $j->tipe_konseling,
            ]);

        return view('konseli.pengajuan', compact('assessments', 'konselors', 'jadwals'));
    }

    public function storePengajuan(Request $request)
    {
        $validated = $request->validate([
            'id_assessment' => ['required', 'exists:assessments,id_assessment'],
            'id_konselor'   => ['required', 'exists:konselor,id_konselor'],
            'id_jadwal'     => ['required', 'exists:jadwal,id_jadwal'],
        ], [
            'id_assessment.required' => 'Pilih assessment yang akan digunakan.',
            'id_konselor.required'   => 'Pilih konselor terlebih dahulu.',
            'id_jadwal.required'     => 'Pilih jadwal yang tersedia.',
        ]);

        $konseli = $this->getKonseli();

        // Cek jadwal masih tersedia (bukan sudah terpakai)
        $jadwal = Jadwal::findOrFail($validated['id_jadwal']);
        if ($jadwal->status_jadwal !== 'tersedia') {
            return back()
                ->withInput()
                ->withErrors(['id_jadwal' => 'Jadwal yang dipilih sudah tidak tersedia. Silakan pilih jadwal lain.']);
        }

        // Cek assessment milik konseli ini
        $assessment = Assessment::findOrFail($validated['id_assessment']);
        if ($assessment->id_konseli !== $konseli->id_konseli) {
            return back()
                ->withInput()
                ->withErrors(['id_assessment' => 'Assessment tidak valid.']);
        }

        PengajuanKonseling::create([
            'id_konseli'       => $konseli->id_konseli,
            'id_konselor'      => $validated['id_konselor'],
            'id_assessment'    => $validated['id_assessment'],
            'id_jadwal'        => $validated['id_jadwal'],
            'status_pengajuan' => 'menunggu',
        ]);

        // Tandai jadwal sudah terpakai agar tidak bisa dipilih konseli lain
        $jadwal->update(['status_jadwal' => 'terpakai']);

        return redirect()
            ->route('konseli.riwayat')
            ->with('success', 'Pengajuan konseling berhasil dikirim. Silakan pantau status pengajuanmu di riwayat konseling.');
    }

    // ─── Riwayat ──────────────────────────────────────────────────────────────

    public function riwayat()
    {
        $konseli = $this->getKonseli();

        $riwayat = PengajuanKonseling::where('id_konseli', $konseli->id_konseli)
            ->with(['konselor.user', 'jadwal'])
            ->latest('created_at')
            ->get()
            ->map(fn($p) => [
                'tanggal'  => $p->created_at,
                'konselor' => $p->konselor?->user?->nama ?? '-',
                'jenis'    => $p->jadwal?->tipe_konseling ?? '-',
                'status'   => $p->status_pengajuan,
            ]);

        return view('konseli.riwayat', compact('riwayat'));
    }

    // ─── Jadwal ───────────────────────────────────────────────────────────────

    public function jadwal()
    {
        $konseli = $this->getKonseli();

        $jadwals = PengajuanKonseling::where('id_konseli', $konseli->id_konseli)
            ->with(['konselor.user', 'jadwal'])
            ->get()
            ->map(fn($p) => [
                'tanggal'  => $p->jadwal?->tanggal,
                'jam'      => $p->jadwal?->jam,
                'konselor' => $p->konselor?->user?->nama ?? '-',
                'status'   => $p->status_pengajuan,
            ]);

        return view('konseli.jadwal', compact('jadwals'));
    }

    // ─── Profil ───────────────────────────────────────────────────────────────

    public function profil()
    {
        $user    = auth()->user();
        $konseli = $this->getKonseli();

        return view('konseli.profil', [
            'profile' => (object) [
                'nama'   => $user->nama,
                'email'  => $user->email,
                'asal'   => $konseli?->asal ?? '-',
                'no_hp'  => $konseli?->no_hp ?? '-',
                'gender' => $konseli?->gender ?? '-',
                'foto'   => $konseli?->foto,
                'foto_url' => $konseli?->foto
                    ? Storage::disk('public')->url($konseli->foto)
                    : null,
            ],
        ]);
    }
}
