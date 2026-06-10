<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Konseli;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'nama'     => $request->nama,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'konseli',
        ]);

        // Buat record konseli kosong agar relasi tidak null
        Konseli::create(['id_user' => $user->id_user]);

        event(new Registered($user));
        Auth::login($user);

        // Langsung ke setup profil setelah register
        return redirect()->route('konseli.profile.setup')
            ->with('info', 'Akun berhasil dibuat! Lengkapi profil kamu untuk mulai menggunakan layanan konseling.');
    }
}
