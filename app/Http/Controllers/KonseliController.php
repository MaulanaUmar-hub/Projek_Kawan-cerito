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
