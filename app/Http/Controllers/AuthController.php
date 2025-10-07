<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pasien;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session()->has('user_id')) {
            return redirect()->route('home');
        }
        return view('auth');
    }

    public function showRegister()
    {
        if (session()->has('user_id')) {
            return redirect()->route('home');
        }
        return view('auth', ['mode' => 'register']);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        try {
            // Find user by username
            $user = User::where('username', $validated['username'])->first();
            
            if (!$user || !Hash::check($validated['password'], $user->password_hash)) {
                return back()->withErrors(['error' => 'Invalid credentials'])->withInput();
            }

            // Set session
            session([
                'user_id' => $user->user_id,
                'username' => $user->username,
                'role' => $user->role->nama_role ?? 'user',
            ]);

            // Redirect based on role
            if (in_array($user->role->nama_role, ['admin', 'staff'])) {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('home');
            }

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Login failed. Please try again.'])->withInput();
        }
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'unique:user,username'],
            'email' => ['required', 'email', 'unique:pasien,email'],
            'password' => ['required', 'string', 'min:6'],
            'password_confirmation' => ['required', 'same:password'],
            'nama' => ['required', 'string'],
            'no_hp' => ['required', 'string'],
            'tgl_lahir' => ['required', 'date'],
        ]);

        try {
            // Get user role
            $userRole = Role::where('nama_role', 'user')->first();
            if (!$userRole) {
                return back()->withErrors(['error' => 'User role not found'])->withInput();
            }

            // Create user account
            $user = User::create([
                'username' => $validated['username'],
                'password_hash' => Hash::make($validated['password']),
                'role_id' => $userRole->role_id,
            ]);

            // Create patient profile
            $pasien = Pasien::create([
                'user_id' => $user->user_id,
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'no_hp' => $validated['no_hp'],
                'tgl_lahir' => $validated['tgl_lahir'],
            ]);

            // After registration, redirect to sign-in with success message
            return redirect()->route('auth')->with('success', 'Registration successful! Please sign in.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Registration failed. Please try again.'])->withInput();
        }
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('auth');
    }
}


