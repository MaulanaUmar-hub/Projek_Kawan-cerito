<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Konselor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KonselorSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat user dulu
        $user = User::create([
            'nama'     => 'konselor',
            'email'    => 'konselor@example.com',
            'password' => Hash::make('12345678'),
            'role'     => 'konselor',
        ]);

        // 2. Buat profil konselor, hubungkan ke user lewat id_user
        Konselor::create([
            'id_user'      => $user->id_user,
            'spesialisasi' => 'Kesehatan Mental',
            'peminatan'    => 'Anxiety & Depresi',
            'no_hp'        => '081234567890',
            'gender'       => 'P',
            'link_whatsapp' => 'https://wa.me/081234567890',
            'status'       => 'aktif', // langsung aktif, skip approval
        ]);
    }
}
