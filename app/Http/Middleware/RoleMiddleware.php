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

        if ($role === 'admin' && strtolower($sessionRole) !== 'admin') {
            return redirect()->route('user.home')->withErrors(['error' => 'Unauthorized. Admin only.']);
        }

        if ($role === 'pasien' && strtolower($sessionRole) !== 'pasien') {
            return redirect()->route('admin.dashboard')->withErrors(['error' => 'Unauthorized. Patients only.']);
        }

        if ($role === 'user' && strtolower($sessionRole) !== 'user') {
            return redirect()->route('admin.dashboard')->withErrors(['error' => 'Unauthorized. Users only.']);
        }

        return $next($request);
    }
}


