<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Konselor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // Konselor: cek status sebelum redirect agar tidak terjadi loop
        if ($user->role === 'konselor') {
            $konselor = Konselor::where('id_user', $user->id_user)->first();

            if ($konselor?->status === 'pending') {
                return redirect()->route('konselor.pending');
            }

            if ($konselor?->status === 'ditolak') {
                // Logout langsung — jangan biarkan sesi aktif untuk konselor ditolak
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->withErrors(['email' => 'Pendaftaran Anda telah ditolak oleh admin.']);
            }
        }

        return redirect()->intended(match ($user->role) {
            'admin'    => route('admin.dashboard'),
            'konselor' => route('konselor.dashboard'),
            default    => route('konseli.dashboard'),
        });
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
