<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthSession
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('user_id')) {
            // Remember intended test selection if user was heading to booking
            if ($request->has('test_id')) {
                session(['intended_test_id' => $request->get('test_id')]);
            }
            return redirect()->route('auth')->withErrors(['error' => 'Please login to continue.']);
        }
        return $next($request);
    }
}


