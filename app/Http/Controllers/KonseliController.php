<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class KonseliController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $dashboard = $user
            ? $this->buildDashboardData($user)
            : $this->fallbackDashboardData(null);

        return view('konseli.dashboard', $dashboard);
    }

    public function assessment()
    {
        return view('konseli.assessment', [
            'lastAssessment' => (object) [
                'tanggal' => now()->subDays(3),
                'keluhan' => 'Merasa cemas dan sulit fokus dalam beberapa hari terakhir.',
                'urgensi' => 'Sedang',
                'jenis_konseling' => 'Chat',
            ],
        ]);
    }

    public function storeAssessment(Request $request)
    {
        return back()->with('success', 'Assessment berhasil disimpan. Kamu dapat melanjutkan ke pengajuan konseling.');
    }

    public function pengajuan()
    {
        return view('konseli.pengajuan', [
            'assessments' => collect([
                ['id' => 1, 'label' => 'Assessment 03 Juni 2026 - Kecemasan ringan'],
                ['id' => 2, 'label' => 'Assessment 28 Mei 2026 - Stres akademik'],
            ]),
            'konselors' => collect([
                ['id' => 1, 'nama' => 'Dr. Maya Putri, M.Psi', 'spesialisasi' => 'Kecemasan dan stres', 'status' => 'tersedia'],
                ['id' => 2, 'nama' => 'Raka Pratama, M.Psi', 'spesialisasi' => 'Relasi dan emosi', 'status' => 'tersedia'],
                ['id' => 3, 'nama' => 'Nadia Larasati, M.Psi', 'spesialisasi' => 'Pengembangan diri', 'status' => 'penuh'],
            ]),
            'jadwals' => collect([
                ['id' => 1, 'label' => '04 Juni 2026, 09:00', 'status' => 'tersedia'],
                ['id' => 2, 'label' => '04 Juni 2026, 13:30', 'status' => 'tersedia'],
                ['id' => 3, 'label' => '05 Juni 2026, 10:00', 'status' => 'penuh'],
            ]),
        ]);
    }

    public function storePengajuan(Request $request)
    {
        return back()->with('success', 'Pengajuan konseling berhasil dikirim. Silakan pantau status pengajuanmu di riwayat konseling.');
    }

    public function riwayat()
    {
        return view('konseli.riwayat', [
            'riwayat' => collect([
                ['tanggal' => now()->subDays(2), 'konselor' => 'Dr. Maya Putri, M.Psi', 'jenis' => 'Chat Konseling', 'status' => 'selesai'],
                ['tanggal' => now()->subDays(6), 'konselor' => 'Raka Pratama, M.Psi', 'jenis' => 'Video Konseling', 'status' => 'disetujui'],
                ['tanggal' => now()->subDays(10), 'konselor' => 'Nadia Larasati, M.Psi', 'jenis' => 'WhatsApp Konseling', 'status' => 'menunggu'],
            ]),
        ]);
    }

    public function jadwal()
    {
        return view('konseli.jadwal', [
            'jadwals' => collect([
                ['tanggal' => now()->addDay(), 'jam' => '09:00', 'konselor' => 'Dr. Maya Putri, M.Psi', 'status' => 'disetujui'],
                ['tanggal' => now()->addDays(3), 'jam' => '13:30', 'konselor' => 'Raka Pratama, M.Psi', 'status' => 'menunggu'],
                ['tanggal' => now()->subDays(4), 'jam' => '10:00', 'konselor' => 'Nadia Larasati, M.Psi', 'status' => 'selesai'],
            ]),
        ]);
    }

    public function profil()
    {
        return view('konseli.profil', [
            'profile' => (object) [
                'nama' => auth()->user()->nama ?? auth()->user()->name ?? 'Preview Konseli',
                'email' => auth()->user()->email ?? 'konseli@example.com',
                'asal' => auth()->user()->asal ?? 'Kawan Cerito',
                'no_hp' => auth()->user()->no_hp ?? '0812-0000-0000',
                'gender' => auth()->user()->gender ?? 'N',
            ],
        ]);
    }

    public function setupProfile()
    {
        return view('konseli.profile.setup', [
            'user' => auth()->user(),
        ]);
    }

    public function storeProfileSetup(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:L,P,N'],
            'asal' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:30'],
            'foto' => ['nullable', 'image', 'max:2048'],
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'gender.required' => 'Pilih gender yang paling sesuai.',
            'gender.in' => 'Pilihan gender tidak valid.',
            'asal.required' => 'Asal wajib diisi.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'foto.image' => 'File foto harus berupa gambar.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        $user = auth()->user();
        $user->update([
            'nama' => $validated['nama'],
            'gender' => $validated['gender'],
            'asal' => $validated['asal'],
            'no_hp' => $validated['no_hp'],
        ]);

        // TODO: Simpan path foto ke kolom profil ketika struktur database foto sudah tersedia.

        return redirect()
            ->route('konseli.dashboard')
            ->with('success', 'Profil berhasil dilengkapi. Selamat datang di dashboard Kawan Cerito.');
    }

    private function buildDashboardData($user): array
    {
        $pengajuan = collect();
        $assessmentTerakhir = null;
        $jadwalBerikutnya = null;
        $aktivitas = collect();

        if (
            class_exists(\App\Models\PengajuanKonseling::class)
            && Schema::hasTable('pengajuan_konselings')
        ) {
            $pengajuan = $user->pengajuanAsKonseli()
                ->with('konselor')
                ->latest()
                ->get();
        }

        if (
            class_exists(\App\Models\Assessment::class)
            && Schema::hasTable('assessments')
        ) {
            $assessmentTerakhir = $user->assessments()
                ->latest()
                ->first();
        }

        if (
            class_exists(\App\Models\Jadwal::class)
            && Schema::hasTable('jadwals')
        ) {
            $jadwalBerikutnya = $user->pengajuanAsKonseli()
                ->whereHas('jadwal', fn ($query) => $query->whereDate('tanggal', '>=', now()->toDateString()))
                ->with(['jadwal', 'konselor'])
                ->latest()
                ->first();
        }

        if (
            class_exists(\App\Models\ActivityLog::class)
            && Schema::hasTable('activity_logs')
        ) {
            $aktivitas = $user->activityLogs()
                ->latest()
                ->limit(5)
                ->get();
        }

        if ($pengajuan->isEmpty()) {
            return $this->fallbackDashboardData($user);
        }

        $statusCounts = [
            'pending' => $pengajuan->where('status', 'pending')->count(),
            'disetujui' => $pengajuan->whereIn('status', ['disetujui', 'approved'])->count(),
            'selesai' => $pengajuan->whereIn('status', ['selesai', 'completed'])->count(),
        ];

        return [
            'stats' => [
                ['label' => 'Total Konseling', 'value' => $pengajuan->count(), 'icon' => 'bx-conversation', 'color' => 'primary'],
                ['label' => 'Pengajuan Menunggu', 'value' => $statusCounts['pending'], 'icon' => 'bx-time-five', 'color' => 'warning'],
                ['label' => 'Konseling Disetujui', 'value' => $statusCounts['disetujui'], 'icon' => 'bx-check-circle', 'color' => 'success'],
                ['label' => 'Konseling Selesai', 'value' => $statusCounts['selesai'], 'icon' => 'bx-badge-check', 'color' => 'info'],
            ],
            'statusCounts' => $statusCounts,
            'riwayatTerbaru' => $pengajuan->take(5),
            'assessmentTerakhir' => $assessmentTerakhir,
            'jadwalBerikutnya' => $jadwalBerikutnya,
            'aktivitas' => $aktivitas->take(5),
            'isFallback' => false,
        ];
    }

    private function fallbackDashboardData($user): array
    {
        $statusCounts = [
            'pending' => 1,
            'disetujui' => 2,
            'selesai' => 4,
        ];

        return [
            'stats' => [
                ['label' => 'Total Konseling', 'value' => 7, 'icon' => 'bx-conversation', 'color' => 'primary'],
                ['label' => 'Pengajuan Menunggu', 'value' => 1, 'icon' => 'bx-time-five', 'color' => 'warning'],
                ['label' => 'Konseling Disetujui', 'value' => 2, 'icon' => 'bx-check-circle', 'color' => 'success'],
                ['label' => 'Konseling Selesai', 'value' => 4, 'icon' => 'bx-badge-check', 'color' => 'info'],
            ],
            'statusCounts' => $statusCounts,
            'riwayatTerbaru' => collect([
                (object) ['tanggal' => now()->subDays(2), 'konselor' => (object) ['nama' => 'Dr. Maya Putri, M.Psi'], 'status' => 'selesai'],
                (object) ['tanggal' => now()->subDays(8), 'konselor' => (object) ['nama' => 'Raka Pratama, M.Psi'], 'status' => 'disetujui'],
                (object) ['tanggal' => now()->subDays(14), 'konselor' => (object) ['nama' => 'Nadia Larasati, M.Psi'], 'status' => 'pending'],
            ]),
            'assessmentTerakhir' => (object) [
                'created_at' => now()->subDays(3),
                'ringkasan_hasil' => 'Indikasi stres ringan. Disarankan melanjutkan konseling suportif dan latihan regulasi emosi.',
            ],
            'jadwalBerikutnya' => (object) [
                'tanggal' => now()->addDays(2),
                'jam' => '10:00',
                'konselor' => (object) ['nama' => 'Dr. Maya Putri, M.Psi'],
                'status' => 'disetujui',
            ],
            'aktivitas' => collect([
                (object) ['created_at' => now()->subHours(4), 'aktivitas' => 'Jadwal konseling disetujui'],
                (object) ['created_at' => now()->subDay(), 'aktivitas' => 'Pengajuan konseling dikirim'],
                (object) ['created_at' => now()->subDays(3), 'aktivitas' => 'Assessment awal dibuat'],
                (object) ['created_at' => now()->subDays(9), 'aktivitas' => 'Konseling selesai'],
            ]),
            'isFallback' => true,
        ];
    }
}
