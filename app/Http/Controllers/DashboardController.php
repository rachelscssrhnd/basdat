<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\HasilTesHeader;

class DashboardController extends Controller
{
    public function index()
    {
        if (!session()->has('user_id')) {
            return redirect()->route('login');
        }

        $role = session('role');
        $userName = session('user_name');

        try {
            $recentBookings = Booking::query()->orderByDesc('booking_id')->limit(5)->get();
        } catch (\Throwable $e) {
            $recentBookings = collect();
        }

        try {
            $recentHasil = HasilTesHeader::query()->orderByDesc('hasil_id')->limit(5)->get();
        } catch (\Throwable $e) {
            $recentHasil = collect();
        }

        if ($role === 'admin') {
            return view('dashboard_admin', compact('userName', 'recentBookings', 'recentHasil'));
        }

        return view('dashboard', compact('userName', 'recentBookings', 'recentHasil'));
    }
}


