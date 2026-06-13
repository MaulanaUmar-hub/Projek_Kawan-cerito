<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureKonseliProfileComplete
{
    /**
     * Route names yang boleh diakses meski profil belum lengkap.
     */
    private array $except = [
        'konseli.profile.setup',
        'konseli.profile.setup.store',
        'logout',
        'profile.edit',
        'profile.update',
        'profile.destroy',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Hanya berlaku untuk role konseli
        if (!$user || $user->role !== 'konseli') {
            return $next($request);
        }

        // Bypass untuk route yang dikecualikan
        if ($request->routeIs(...$this->except)) {
            return $next($request);
        }

        // Cek apakah record konseli sudah ada dan sudah diisi
        $konseli = $user->konseli;

        $profileComplete = $konseli
            && $konseli->asal
            && $konseli->no_hp
            && $konseli->gender;

        if (!$profileComplete) {
            return redirect()->route('konseli.profile.setup')
                ->with('info', 'Lengkapi profil kamu terlebih dahulu untuk mengakses fitur konseling.');
        }

        return $next($request);
    }
}
