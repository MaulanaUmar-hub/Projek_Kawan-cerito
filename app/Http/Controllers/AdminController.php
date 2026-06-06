<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    public function index()
    {
        $konselor = $this->konselorFallback();
        $totalPengguna = Schema::hasTable('users') ? User::count() : 126;

        $pending = $konselor->where('status', 'pending')->values();
        $aktif = $konselor->where('status', 'aktif')->values();
        $ditolak = $konselor->where('status', 'ditolak')->values();

        return view('admin.dashboard', [
            'stats' => [
                ['label' => 'Total Pengguna', 'value' => $totalPengguna ?: 126, 'icon' => 'U', 'color' => 'primary'],
                ['label' => 'Konselor Pending', 'value' => $pending->count(), 'icon' => 'P', 'color' => 'warning'],
                ['label' => 'Konselor Aktif', 'value' => $aktif->count(), 'icon' => 'K', 'color' => 'success'],
                ['label' => 'Konselor Ditolak', 'value' => $ditolak->count(), 'icon' => 'T', 'color' => 'danger'],
            ],
            'konselorPending' => $pending,
            'konselorAktif' => $aktif,
            'konselorDitolak' => $ditolak,
        ]);
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
}
