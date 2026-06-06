<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', $this->dashboardData());
    }

    public function approvalKonselor()
    {
        return view('admin.approval-konselor', $this->dashboardData());
    }

    public function users()
    {
        return view('admin.users', $this->dashboardData());
    }

    public function konselor()
    {
        return view('admin.konselor', $this->dashboardData());
    }

    public function konselorDetail()
    {
        return view('admin.konselor-detail', $this->dashboardData());
    }

    public function jadwal()
    {
        return view('admin.jadwal', $this->dashboardData());
    }

    public function activityLog()
    {
        return view('admin.activity-log', $this->dashboardData());
    }

    private function dashboardData(): array
    {
        $konselor = $this->konselorFallback();
        $users = $this->usersFallback();
        $jadwal = $this->jadwalFallback();
        $logs = $this->activityFallback();
        $totalPengguna = Schema::hasTable('users') ? User::count() : 126;

        $pending = $konselor->where('status', 'pending')->values();
        $aktif = $konselor->where('status', 'aktif')->values();
        $ditolak = $konselor->where('status', 'ditolak')->values();

        return [
            'stats' => [
                ['label' => 'Total Pengguna', 'value' => $totalPengguna ?: 126, 'icon' => 'U', 'color' => 'primary'],
                ['label' => 'Konselor Pending', 'value' => $pending->count(), 'icon' => 'P', 'color' => 'warning'],
                ['label' => 'Konselor Aktif', 'value' => $aktif->count(), 'icon' => 'K', 'color' => 'success'],
                ['label' => 'Konselor Ditolak', 'value' => $ditolak->count(), 'icon' => 'T', 'color' => 'danger'],
            ],
            'konselorPending' => $pending,
            'konselorAktif' => $aktif,
            'konselorDitolak' => $ditolak,
            'users' => $users,
            'jadwal' => $jadwal,
            'activityLogs' => $logs,
        ];
    }

    private function konselorFallback()
    {
        return collect([
            [
                'nama' => 'Dr. Maya Putri',
                'email' => 'maya.putri@kawancerito.test',
                'asal' => 'Universitas Sriwijaya',
                'no_hp' => '0812-3456-7890',
                'spesialisasi' => 'Kecemasan dan stres',
                'tanggal' => now()->subHours(8),
                'status' => 'pending',
            ],
            [
                'nama' => 'Raka Pratama, M.Psi',
                'email' => 'raka.pratama@kawancerito.test',
                'asal' => 'Klinik Cerah',
                'no_hp' => '0812-2233-4455',
                'spesialisasi' => 'Relasi dan emosi',
                'tanggal' => now()->subDay(),
                'status' => 'pending',
            ],
            [
                'nama' => 'Nadia Larasati, M.Psi',
                'email' => 'nadia.larasati@kawancerito.test',
                'asal' => 'Kawan Cerito',
                'no_hp' => '0812-5555-9090',
                'spesialisasi' => 'Pengembangan diri',
                'tanggal' => now()->subDays(4),
                'status' => 'aktif',
            ],
            [
                'nama' => 'Dr. Aditya Nugraha',
                'email' => 'aditya.nugraha@kawancerito.test',
                'asal' => 'RS Harmoni',
                'no_hp' => '0813-1111-2020',
                'spesialisasi' => 'Konseling keluarga',
                'tanggal' => now()->subDays(6),
                'status' => 'aktif',
            ],
            [
                'nama' => 'Sinta Maharani',
                'email' => 'sinta.maharani@kawancerito.test',
                'asal' => 'Mandiri',
                'no_hp' => '0813-3333-8080',
                'spesialisasi' => 'Belum diverifikasi',
                'tanggal' => now()->subDays(9),
                'status' => 'ditolak',
            ],
        ]);
    }

    private function usersFallback()
    {
        return collect([
            ['nama' => 'Alya Prameswari', 'email' => 'alya@kawancerito.test', 'role' => 'konseli', 'status' => 'aktif', 'tanggal' => now()->subDay()],
            ['nama' => 'Bagas Saputra', 'email' => 'bagas@kawancerito.test', 'role' => 'konseli', 'status' => 'aktif', 'tanggal' => now()->subDays(2)],
            ['nama' => 'Nadia Larasati', 'email' => 'nadia.larasati@kawancerito.test', 'role' => 'konselor', 'status' => 'aktif', 'tanggal' => now()->subDays(4)],
            ['nama' => 'Sinta Maharani', 'email' => 'sinta.maharani@kawancerito.test', 'role' => 'konselor', 'status' => 'ditolak', 'tanggal' => now()->subDays(9)],
        ]);
    }

    private function jadwalFallback()
    {
        return collect([
            ['tanggal' => now()->addDay(), 'jam' => '09:00', 'konseli' => 'Alya Prameswari', 'konselor' => 'Nadia Larasati, M.Psi', 'tipe' => 'Chat Konseling', 'status' => 'tersedia'],
            ['tanggal' => now()->addDays(2), 'jam' => '13:30', 'konseli' => 'Bagas Saputra', 'konselor' => 'Dr. Aditya Nugraha', 'tipe' => 'Video Konseling', 'status' => 'aktif'],
            ['tanggal' => now()->addDays(4), 'jam' => '10:00', 'konseli' => 'Dimas Arianto', 'konselor' => 'Nadia Larasati, M.Psi', 'tipe' => 'WhatsApp', 'status' => 'pending'],
        ]);
    }

    private function activityFallback()
    {
        return collect([
            ['waktu' => now()->subMinutes(12), 'user' => 'Admin', 'role' => 'admin', 'aktivitas' => 'Membuka dashboard approval konselor'],
            ['waktu' => now()->subHours(1), 'user' => 'Dr. Maya Putri', 'role' => 'konselor', 'aktivitas' => 'Mengirim pengajuan menjadi konselor'],
            ['waktu' => now()->subHours(3), 'user' => 'Alya Prameswari', 'role' => 'konseli', 'aktivitas' => 'Mengirim pengajuan konseling'],
            ['waktu' => now()->subDay(), 'user' => 'Nadia Larasati', 'role' => 'konselor', 'aktivitas' => 'Hasil konseling dibuat'],
        ]);
    }
}
