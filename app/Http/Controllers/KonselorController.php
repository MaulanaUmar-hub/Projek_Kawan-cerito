<?php

namespace App\Http\Controllers;

class KonselorController extends Controller
{
    public function index()
    {
        return view('konselor.dashboard', $this->dashboardData());
    }

    public function pengajuan()
    {
        return view('konselor.pengajuan', $this->dashboardData());
    }

    public function jadwal()
    {
        return view('konselor.jadwal', $this->dashboardData());
    }

    public function riwayat()
    {
        return view('konselor.riwayat', $this->dashboardData());
    }

    public function profil()
    {
        return view('konselor.profil', $this->dashboardData());
    }

    private function dashboardData(): array
    {
        $user = auth()->user();
        $name = $user->nama ?? $user->name ?? 'Konselor';

        return [
            'name' => $name,
            'profile' => [
                'nama' => $name,
                'email' => $user->email ?? 'konselor@kawancerito.test',
                'spesialisasi' => 'Konseling remaja dan manajemen stres',
                'status' => 'Aktif',
            ],
            'stats' => [
                ['label' => 'Total Konseli', 'value' => 28, 'icon' => 'K', 'color' => 'primary'],
                ['label' => 'Pengajuan Baru', 'value' => 6, 'icon' => 'P', 'color' => 'warning'],
                ['label' => 'Sesi Hari Ini', 'value' => 4, 'icon' => 'J', 'color' => 'info'],
                ['label' => 'Sesi Selesai', 'value' => 19, 'icon' => 'S', 'color' => 'success'],
            ],
            'pengajuanTerbaru' => collect([
                ['nama' => 'Alya Prameswari', 'tanggal' => now()->subHours(2), 'keluhan' => 'Cemas menjelang ujian dan sulit tidur.', 'status' => 'baru'],
                ['nama' => 'Bagas Saputra', 'tanggal' => now()->subDay(), 'keluhan' => 'Stres pekerjaan dan konflik dengan rekan tim.', 'status' => 'pending'],
                ['nama' => 'Citra Lestari', 'tanggal' => now()->subDays(2), 'keluhan' => 'Mudah panik saat berada di tempat ramai.', 'status' => 'disetujui'],
            ]),
            'jadwalHariIni' => collect([
                ['nama' => 'Dimas Arianto', 'jam' => '09:00', 'status' => 'aktif'],
                ['nama' => 'Eka Rahma', 'jam' => '11:30', 'status' => 'disetujui'],
                ['nama' => 'Farah Nabila', 'jam' => '15:00', 'status' => 'pending'],
            ]),
            'aktivitas' => collect([
                ['aktivitas' => 'Pengajuan Alya Prameswari disetujui', 'waktu' => now()->subMinutes(35)],
                ['aktivitas' => 'Hasil konseling Dimas Arianto dibuat', 'waktu' => now()->subHours(3)],
                ['aktivitas' => 'Jadwal Eka Rahma diperbarui', 'waktu' => now()->subDay()],
            ]),
            'produktifitas' => [
                ['label' => 'Selesai bulan ini', 'value' => 19, 'color' => '#03c3ec'],
                ['label' => 'Konseling aktif', 'value' => 7, 'color' => '#696cff'],
                ['label' => 'Konseling tertunda', 'value' => 6, 'color' => '#ffab00'],
            ],
            'riwayatKonseling' => collect([
                ['tanggal' => now()->subDays(1), 'nama' => 'Dimas Arianto', 'status' => 'selesai', 'hasil' => 'Latihan grounding dan rencana tidur terstruktur.'],
                ['tanggal' => now()->subDays(3), 'nama' => 'Nadia Putri', 'status' => 'selesai', 'hasil' => 'Journaling emosi dan evaluasi pemicu stres.'],
                ['tanggal' => now()->subDays(5), 'nama' => 'Rizky Maulana', 'status' => 'aktif', 'hasil' => 'Sesi lanjutan dijadwalkan pekan depan.'],
            ]),
        ];
    }
}
