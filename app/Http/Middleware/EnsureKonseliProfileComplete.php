<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureKonseliProfileComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || $user->role !== 'konseli') {
            return $next($request);
        }

        $profile = $user->konseli;
        $isComplete = $profile
            && filled($profile->asal)
            && filled($profile->no_hp)
            && filled($profile->gender);

        if (!$isComplete) {
            return redirect()->route('konseli.profile.setup');
        }

        return $next($request);
    }
}
