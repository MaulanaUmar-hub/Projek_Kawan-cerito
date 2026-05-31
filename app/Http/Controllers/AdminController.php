<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    public function index()
    {
        $totalPengguna = Schema::hasTable('users') ? User::count() : 126;
        $totalKonselor = Schema::hasTable('users') ? User::where('role', 'konselor')->count() : 14;

        return view('admin.dashboard', [
            'stats' => [
                ['label' => 'Total Pengguna', 'value' => $totalPengguna ?: 126, 'icon' => 'U', 'color' => 'primary'],
                ['label' => 'Total Konselor', 'value' => $totalKonselor ?: 14, 'icon' => 'K', 'color' => 'info'],
                ['label' => 'Total Konseling', 'value' => 86, 'icon' => 'S', 'color' => 'success'],
                ['label' => 'Pengajuan Aktif', 'value' => 18, 'icon' => 'P', 'color' => 'warning'],
            ],
            'aktivitasSistem' => collect([
                ['waktu' => now()->subMinutes(12), 'user' => 'Alya Prameswari', 'role' => 'Konseli', 'aktivitas' => 'Pengajuan konseling dibuat', 'status' => 'baru'],
                ['waktu' => now()->subMinutes(45), 'user' => 'Dr. Maya Putri', 'role' => 'Konselor', 'aktivitas' => 'Approval jadwal konseling', 'status' => 'disetujui'],
                ['waktu' => now()->subHours(2), 'user' => 'Admin', 'role' => 'Admin', 'aktivitas' => 'Registrasi konselor diverifikasi', 'status' => 'selesai'],
                ['waktu' => now()->subHours(4), 'user' => 'Dimas Arianto', 'role' => 'Konseli', 'aktivitas' => 'Login pengguna', 'status' => 'aktif'],
                ['waktu' => now()->subDay(), 'user' => 'Raka Pratama', 'role' => 'Konselor', 'aktivitas' => 'Hasil konseling dibuat', 'status' => 'dibuat'],
            ]),
            'penggunaTerbaru' => collect([
                ['nama' => 'Alya Prameswari', 'role' => 'Konseli', 'tanggal' => now()->subDay()],
                ['nama' => 'Bagas Saputra', 'role' => 'Konseli', 'tanggal' => now()->subDays(2)],
                ['nama' => 'Dr. Nadine Sari', 'role' => 'Konselor', 'tanggal' => now()->subDays(4)],
            ]),
            'statistikPlatform' => [
                ['label' => 'Konseli', 'value' => 112, 'color' => '#696cff'],
                ['label' => 'Konselor', 'value' => 14, 'color' => '#03c3ec'],
                ['label' => 'Pengajuan', 'value' => 38, 'color' => '#ffab00'],
                ['label' => 'Konseling Selesai', 'value' => 86, 'color' => '#71dd37'],
            ],
            'pengajuanMenunggu' => collect([
                ['nama' => 'Citra Lestari', 'konselor' => 'Belum ditentukan', 'tanggal' => now()->subHours(6), 'status' => 'pending'],
                ['nama' => 'Farah Nabila', 'konselor' => 'Dr. Maya Putri', 'tanggal' => now()->subDay(), 'status' => 'pending'],
                ['nama' => 'Gilang Prakoso', 'konselor' => 'Raka Pratama', 'tanggal' => now()->subDays(2), 'status' => 'pending'],
            ]),
            'jadwalMendatang' => collect([
                ['tanggal' => now()->addDay(), 'jam' => '09:00', 'konseli' => 'Alya Prameswari', 'konselor' => 'Dr. Maya Putri'],
                ['tanggal' => now()->addDays(2), 'jam' => '13:30', 'konseli' => 'Bagas Saputra', 'konselor' => 'Raka Pratama'],
                ['tanggal' => now()->addDays(3), 'jam' => '10:00', 'konseli' => 'Dimas Arianto', 'konselor' => 'Nadia Larasati'],
            ]),
        ]);
    }
}
