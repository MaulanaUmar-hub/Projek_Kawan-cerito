<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Konselor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KonselorRegisterController extends Controller
{
    public function create()
    {
        return view('auth.register-konselor');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email',
            'password'       => 'required|min:8|confirmed',
            'spesialisasi'   => 'required|string|max:255',
            'no_hp'          => 'required|string|max:20',
            'gender'         => 'required|in:L,P,N',
            'link_whatsapp'  => 'nullable|url',
        ]);

        // Buat akun user
        $user = User::create([
            'nama'     => $request->nama,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'konselor',
        ]);

        // Buat profil konselor dengan status pending
        Konselor::create([
            'id_user'       => $user->id_user,
            'spesialisasi'  => $request->spesialisasi,
            'no_hp'         => $request->no_hp,
            'gender'        => $request->gender,
            'link_whatsapp' => $request->link_whatsapp,
            'status'        => 'pending',
        ]);

        return redirect()->route('login')
            ->with('status', 'Pendaftaran berhasil! Akun Anda sedang menunggu persetujuan admin.');
    }
}
