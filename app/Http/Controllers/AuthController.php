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

    public function showLoginForm()
    {
        return $this->showLogin();
    }

    public function showRegister()
    {
        if (session()->has('user_id')) {
            return redirect()->route('home');
        }
        return view('auth', ['mode' => 'register']);
    }

    public function showRegisterForm()
    {
        return $this->showRegister();
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        try {
            // Find user by username or email (email is in pasien table)
            $input = $validated['username'];
            $user = User::where('username', $input)->first();
            
            // If not found by username, try to find by email in pasien table
            if (!$user) {
                $pasien = Pasien::where('email', $input)->first();
                if ($pasien) {
                    $user = $pasien->user;
                }
            }
            
            if (!$user || !Hash::check($validated['password'], $user->password_hash)) {
                return back()->withErrors(['error' => 'Invalid credentials'])->withInput();
            }

            // Set session
            session([
                'user_id' => $user->user_id,
                'username' => $user->username,
                'role' => $user->role->name ?? 'User',
                'role_name' => strtolower($user->role->slug ?? 'user'),
            ]);

            // Redirect based on role
            \App\Models\LogActivity::create([
                'user_id' => $user->user_id,
                'action' => 'User logged in',
                'created_at' => now(),
            ]);

            if (strtolower($user->role->slug) === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Login successful!');
            }
            return redirect()->route('user.home')->with('success', 'Login successful!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Login failed. Please try again.'])->withInput();
        }
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'unique:user,username'],
            'email' => ['required', 'email', 'unique:pasien,email'],
            'password' => ['required', 'string', 'min:8'],
            'password_confirmation' => ['required', 'same:password'],
            'nama' => ['required', 'string'],
            'no_hp' => ['required', 'string'],
            'tgl_lahir' => ['required', 'date'],
        ]);

        try {
            \DB::beginTransaction();
            // Get or create default 'pasien' role
            $userRole = Role::where('slug', 'pasien')->first();
            if (!$userRole) {
                $userRole = Role::create([
                    'name' => 'Pasien',
                    'slug' => 'pasien',
                ]);
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

            \DB::commit();

            // Log
            \App\Models\LogActivity::create([
                'user_id' => $user->user_id,
                'action' => 'User registered',
                'created_at' => now(),
            ]);

            // After registration, redirect to sign-in with success message
            return redirect()->route('auth')->with('success', 'Registrasi berhasil! Silakan login.');

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Registration failed', ['error' => $e->getMessage()]);
            return back()->with('error', $e->getMessage() ?: 'Registrasi gagal. Periksa kembali data Anda.')->withInput();
        }
    }

    public function logout()
    {
        if (session('user_id')) {
            \App\Models\LogActivity::create([
                'user_id' => session('user_id'),
                'action' => 'User logged out',
                'created_at' => now(),
            ]);
        }
        session()->flush();
        return redirect()->route('auth')->with('success', 'Logged out successfully');
    }
}


