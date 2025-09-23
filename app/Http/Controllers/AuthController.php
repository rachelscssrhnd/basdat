<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session()->has('user_id')) {
            return redirect()->route('home');
        }
        return view('login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string'],
            'role' => ['required', 'in:admin,user'],
        ]);

        // Session-only demo auth (no DB dependency)
        session([
            'user_id' => uniqid('session_', true),
            'user_name' => $validated['username'],
            'role' => $validated['role'],
        ]);

        return redirect()->route($validated['role'] === 'admin' ? 'home.admin' : 'home');
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('login');
    }
}


