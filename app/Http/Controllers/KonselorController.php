<?php

namespace App\Http\Controllers;

use App\Models\Konselor;
use App\Models\PengajuanKonseling;
use App\Models\Jadwal;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class KonselorController extends Controller
{
    /**
     * Ambil record Konselor milik user yang sedang login.
     * Return null jika belum ada (seharusnya tidak terjadi setelah registrasi).
     */
    private function getKonselor(): ?Konselor
    {
        return Konselor::where('id_user', auth()->user()->id_user)->first();
    }

    /**
     * Guard: redirect jika status bukan 'aktif'.
     * Return redirect response atau null (berarti boleh lanjut).
     */
    private function cekStatusKonselor(): ?\Illuminate\Http\RedirectResponse
    {
        $konselor = $this->getKonselor();

        if (!$konselor || $konselor->status === 'pending') {
            return redirect()->route('konselor.pending');
        }

        if ($konselor->status === 'ditolak') {
            return redirect()->route('login')
                ->withErrors(['email' => 'Pendaftaran Anda telah ditolak oleh admin.']);
        }

        return null;
    }

    public function pending()
    {
        return view('konselor.pending');
    }

    public function index()
    {
        if ($redirect = $this->cekStatusKonselor()) return $redirect;

        $konselor = $this->getKonselor();

        // --- Stats ---
        $totalKonseli = PengajuanKonseling::where('id_konselor', $konselor->id_konselor)
            ->distinct('id_konseli')
            ->count('id_konseli');

        $pengajuanBaru = PengajuanKonseling::where('id_konselor', $konselor->id_konselor)
            ->where('status_pengajuan', 'menunggu')
            ->count();

        $sesiHariIni = Jadwal::where('id_konselor', $konselor->id_konselor)
            ->whereDate('tanggal', Carbon::today())
            ->count();

        $sesiSelesai = PengajuanKonseling::where('id_konselor', $konselor->id_konselor)
            ->where('status_pengajuan', 'selesai')
            ->count();

        // --- Pengajuan terbaru (5 terakhir) ---
        $pengajuanTerbaru = PengajuanKonseling::with(['konseli.user', 'assessment', 'jadwal'])
            ->where('id_konselor', $konselor->id_konselor)
            ->latest('created_at')
            ->take(5)
            ->get()
            ->map(fn($p) => [
                'id'       => $p->id_pengajuan,
                'nama'     => $p->konseli?->user?->nama ?? '-',
                'tanggal'  => $p->created_at,
                'keluhan'  => $p->assessment?->keluhan ?? '-',
                'status'   => $p->status_pengajuan,
            ]);

        // --- Jadwal hari ini ---
        $jadwalHariIni = Jadwal::with(['pengajuan.konseli.user'])
            ->where('id_konselor', $konselor->id_konselor)
            ->whereDate('tanggal', Carbon::today())
            ->orderBy('jam')
            ->get()
            ->map(fn($j) => [
                'nama'   => $j->pengajuan?->konseli?->user?->nama ?? '(Belum ada konseli)',
                'jam'    => Carbon::parse($j->jam)->format('H:i'),
                'status' => $j->pengajuan?->status_pengajuan ?? $j->status_jadwal,
            ]);

        // --- Aktivitas terbaru ---
        $aktivitas = ActivityLog::where('id_user', auth()->user()->id_user)
            ->latest('created_at')
            ->take(5)
            ->get()
            ->map(fn($a) => [
                'aktivitas' => $a->aktivitas,
                'waktu'     => $a->created_at,
            ]);

        // --- Produktivitas bulan ini ---
        $bulanIni = Carbon::now()->startOfMonth();

        $selesaiBulanIni = PengajuanKonseling::where('id_konselor', $konselor->id_konselor)
            ->where('status_pengajuan', 'selesai')
            ->where('created_at', '>=', $bulanIni)
            ->count();

        $konselingAktif = PengajuanKonseling::where('id_konselor', $konselor->id_konselor)
            ->whereIn('status_pengajuan', ['disetujui', 'aktif'])
            ->count();

        $konselingTertunda = PengajuanKonseling::where('id_konselor', $konselor->id_konselor)
            ->where('status_pengajuan', 'menunggu')
            ->count();

        // --- Riwayat konseling (10 terakhir) ---
        $riwayatKonseling = PengajuanKonseling::with(['konseli.user', 'hasil'])
            ->where('id_konselor', $konselor->id_konselor)
            ->whereIn('status_pengajuan', ['selesai', 'disetujui', 'aktif'])
            ->latest('created_at')
            ->take(10)
            ->get()
            ->map(fn($p) => [
                'tanggal' => $p->created_at,
                'nama'    => $p->konseli?->user?->nama ?? '-',
                'status'  => $p->status_pengajuan,
                'hasil'   => $p->hasil?->catatan_konseling ?? '-',
            ]);

        $name = auth()->user()->nama ?? auth()->user()->name ?? 'Konselor';

        return view('konselor.dashboard', [
            'name'    => $name,
            'profile' => [
                'nama'          => $name,
                'email'         => auth()->user()->email,
                'spesialisasi'  => $konselor->spesialisasi ?? '-',
                'status'        => ucfirst($konselor->status),
            ],
            'stats' => [
                ['label' => 'Total Konseli',    'value' => $totalKonseli,   'icon' => '♟', 'color' => 'primary'],
                ['label' => 'Pengajuan Baru',   'value' => $pengajuanBaru,  'icon' => '✎', 'color' => 'warning'],
                ['label' => 'Sesi Hari Ini',    'value' => $sesiHariIni,    'icon' => '◷', 'color' => 'info'],
                ['label' => 'Sesi Selesai',     'value' => $sesiSelesai,    'icon' => '✓', 'color' => 'success'],
            ],
            'pengajuanTerbaru' => $pengajuanTerbaru,
            'jadwalHariIni'    => $jadwalHariIni,
            'aktivitas'        => $aktivitas,
            'produktifitas'    => [
                ['label' => 'Selesai bulan ini',  'value' => $selesaiBulanIni,   'color' => '#03c3ec'],
                ['label' => 'Konseling aktif',    'value' => $konselingAktif,    'color' => '#696cff'],
                ['label' => 'Konseling tertunda', 'value' => $konselingTertunda, 'color' => '#ffab00'],
            ],
            'riwayatKonseling' => $riwayatKonseling,
        ]);
    }

    public function pengajuan()
    {
        if ($redirect = $this->cekStatusKonselor()) return $redirect;

        $konselor = $this->getKonselor();

        $pengajuanTerbaru = PengajuanKonseling::with(['konseli.user', 'assessment', 'jadwal'])
            ->where('id_konselor', $konselor->id_konselor)
            ->latest('created_at')
            ->get()
            ->map(fn($p) => [
                'id'      => $p->id_pengajuan,
                'nama'    => $p->konseli?->user?->nama ?? '-',
                'tanggal' => $p->created_at,
                'keluhan' => $p->assessment?->keluhan ?? '-',
                'status'  => $p->status_pengajuan,
            ]);

        return view('konselor.pengajuan', [
            'pengajuanTerbaru' => $pengajuanTerbaru,
        ]);
    }

    public function jadwal()
    {
        if ($redirect = $this->cekStatusKonselor()) return $redirect;

        $konselor = $this->getKonselor();

        $jadwalHariIni = Jadwal::with(['pengajuan.konseli.user'])
            ->where('id_konselor', $konselor->id_konselor)
            ->whereDate('tanggal', Carbon::today())
            ->orderBy('jam')
            ->get()
            ->map(fn($j) => [
                'nama'   => $j->pengajuan?->konseli?->user?->nama ?? '(Belum ada konseli)',
                'jam'    => Carbon::parse($j->jam)->format('H:i'),
                'status' => $j->pengajuan?->status_pengajuan ?? $j->status_jadwal,
            ]);

        return view('konselor.jadwal', [
            'jadwalHariIni' => $jadwalHariIni,
        ]);
    }

    public function riwayat()
    {
        if ($redirect = $this->cekStatusKonselor()) return $redirect;

        $konselor = $this->getKonselor();

        $riwayatKonseling = PengajuanKonseling::with(['konseli.user', 'hasil'])
            ->where('id_konselor', $konselor->id_konselor)
            ->latest('created_at')
            ->get()
            ->map(fn($p) => [
                'tanggal' => $p->created_at,
                'nama'    => $p->konseli?->user?->nama ?? '-',
                'status'  => $p->status_pengajuan,
                'hasil'   => $p->hasil?->catatan_konseling ?? '-',
            ]);

        return view('konselor.riwayat', [
            'riwayatKonseling' => $riwayatKonseling,
        ]);
    }

    public function profil()
    {
        if ($redirect = $this->cekStatusKonselor()) return $redirect;

        $konselor = $this->getKonselor();
        $user = auth()->user();

        return view('konselor.profil', [
            'profile' => [
                'nama'         => $user->nama,
                'email'        => $user->email,
                'spesialisasi' => $konselor->spesialisasi ?? '-',
                'peminatan'    => $konselor->peminatan ?? '',
                'catatan_profil' => $konselor->catatan_profil ?? '',
                'foto'         => $konselor->foto,
                'foto_url'     => $konselor->foto ? Storage::disk('public')->url($konselor->foto) : null,
                'status'       => ucfirst($konselor->status),
                'no_hp'        => $konselor->no_hp ?? '-',
                'gender'       => $konselor->gender ?? '-',
                'link_whatsapp' => $konselor->link_whatsapp ?? '-',
            ],
        ]);
    }

    public function updateProfil(Request $request)
    {
        if ($redirect = $this->cekStatusKonselor()) return $redirect;

        $konselor = $this->getKonselor();

        $validated = $request->validate([
            'spesialisasi' => ['nullable', 'string', 'max:255'],
            'peminatan' => ['nullable', 'string', 'max:255'],
            'catatan_profil' => ['nullable', 'string', 'max:500'],
            'foto' => ['nullable', 'image', 'max:2048'],
        ], [
            'catatan_profil.max' => 'Note status maksimal 500 karakter.',
            'foto.image' => 'Foto profil harus berupa gambar.',
            'foto.max' => 'Ukuran foto profil maksimal 2 MB.',
        ]);

        if ($request->hasFile('foto')) {
            if ($konselor->foto) {
                Storage::disk('public')->delete($konselor->foto);
            }

            $validated['foto'] = $request->file('foto')->store('konselor/profil', 'public');
        }

        $konselor->update($validated);

        return back()->with('success', 'Profil konselor berhasil diperbarui.');
    }
}
