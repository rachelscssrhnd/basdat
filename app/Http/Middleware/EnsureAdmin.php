<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('user_id')) {
            return redirect()->route('auth')->withErrors(['error' => 'Please login to continue.']);
        }
        
        if (session('role') !== 'admin') {
            return redirect()->route('home')->withErrors(['error' => 'Unauthorized. Admin access required.']);
        }
        
        return $next($request);
    }
}


