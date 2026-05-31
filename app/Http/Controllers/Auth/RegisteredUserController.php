<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
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
            'asal'     => ['nullable', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'no_hp'    => ['nullable', 'string'],
            'gender'   => ['nullable', 'in:L,P,N'],
        ]);

        $user = User::create([
            'nama'     => $request->nama,
            'asal'     => $request->asal,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'no_hp'    => $request->no_hp,
            'gender'   => $request->gender,
            'role'     => 'konseli', // hardcoded, tidak dari request
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect()->intended(match ($user->role) {
            'admin'    => route('admin.dashboard'),
            'konselor' => route('konselor.dashboard'),
            default    => route('konseli.dashboard'),
        });
    }
}
