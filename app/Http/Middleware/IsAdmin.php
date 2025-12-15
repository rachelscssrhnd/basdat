<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if(session()->has('role_name') && strtolower(session('role_name')) === 'admin'){
            return $next($request);
        }

        return redirect('/'); // kalau bukan admin, balik ke home
    }
}
