<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pasien;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Schema;

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
        // Normalize input to prevent trailing-space login failures
        $request->merge([
            'username' => trim($request->input('username', '')),
        ]);

        $validated = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        try {
            // Find user by username or email (email is in pasien table)
            $input = strtolower($validated['username']);
            $user = User::whereRaw('LOWER(username) = ?', [$input])->first();
            
            // If not found by username, try to find by email in pasien table
            if (!$user) {
                $pasien = Pasien::whereRaw('LOWER(email) = ?', [$input])->first();
                if ($pasien) {
                    $user = $pasien->user;
                }
            }
            
            if (!$user || !Hash::check($validated['password'], $user->password_hash)) {
                return back()
                    ->withErrors(['error' => 'Username atau password salah'])
                    ->withInput();
            }

            if (!$user->role) {
                return back()
                    ->withErrors(['error' => 'Akun belum memiliki role. Hubungi admin.'])
                    ->withInput();
            }

            // Set session with graceful fallback for role columns (name / role_name / slug)
            $roleName = $user->role->slug
                ?? $user->role->name
                ?? $user->role->role_name
                ?? 'User';
            $roleSlug = strtolower($roleName);

            session([
                'user_id' => $user->user_id,
                'username' => $user->username,
                'role' => $roleName,
                'role_name' => $roleSlug,
            ]);

            // Redirect based on role
            \App\Models\LogActivity::create([
                'user_id' => $user->user_id,
                'action' => 'User logged in',
                'created_at' => now(),
            ]);

            // If user intended to book a specific test before login, send them there
            if (session()->has('intended_test_id')) {
                $tid = session()->pull('intended_test_id');
                return redirect()->route('booking', ['test_id' => $tid])->with('success', 'Login successful!');
            }

            if ($roleSlug === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Login successful!');
            }
            return redirect()->route('user.home')->with('success', 'Login successful!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Login failed. Please try again.'])->withInput();
        }
    }

    public function register(Request $request)
    {
        // Trim inputs to avoid hidden whitespace causing login failures
        $request->merge([
            'username' => trim($request->input('username', '')),
            'email' => trim($request->input('email', '')),
        ]);

        $validated = $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:50', 'unique:user,username'],
            'email' => ['required', 'email', 'unique:pasien,email'],
            'password' => ['required', 'string', Password::min(8)],
            'password_confirmation' => ['required', 'same:password'],
            'nama' => ['required', 'string'],
            'no_hp' => ['required', 'string'],
            'tgl_lahir' => ['required', 'date'],
        ]);

        try {
            \DB::beginTransaction();

            // Determine available role columns
            $hasSlug = Schema::hasColumn('role', 'slug');
            $hasName = Schema::hasColumn('role', 'name');
            $hasRoleName = Schema::hasColumn('role', 'role_name');

            // Find pasien role using whichever column exists
            $userRole = null;
            if ($hasSlug) {
                $userRole = Role::where('slug', 'pasien')->first();
            } elseif ($hasRoleName) {
                $userRole = Role::whereRaw('LOWER(role_name) = ?', ['pasien'])->first();
            } elseif ($hasName) {
                $userRole = Role::whereRaw('LOWER(name) = ?', ['pasien'])->first();
            }

            // Build create payload only with existing columns
            if (!$userRole) {
                $payload = [];
                if ($hasName) {
                    $payload['name'] = 'Pasien';
                }
                if ($hasRoleName) {
                    $payload['role_name'] = 'Pasien';
                }
                if ($hasSlug) {
                    $payload['slug'] = 'pasien';
                }
                $userRole = Role::create($payload);
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


