<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Usage: ->middleware('role:admin') or ->middleware('role:user')
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $sessionRole = session('role_name');
        if (!$sessionRole) {
            return redirect()->route('auth')->withErrors(['error' => 'Please login to continue.']);
        }

        // Check role properly
        if ($role === 'admin' && strtolower($sessionRole) !== 'admin') {
            return redirect()->route('auth')->withErrors(['error' => 'Unauthorized. Admin access required.']);
        }

        if ($role === 'pasien' && strtolower($sessionRole) !== 'pasien') {
            return redirect()->route('home')->withErrors(['error' => 'Unauthorized. Patient access required.']);
        }

        if ($role === 'user' && strtolower($sessionRole) !== 'user') {
            return redirect()->route('home')->withErrors(['error' => 'Unauthorized. User access required.']);
        }

        return $next($request);
    }
}


