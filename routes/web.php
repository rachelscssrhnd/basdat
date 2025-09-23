<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HasilTesController;
use App\Http\Controllers\BookingController;

Route::get('/', function () {
    // Always show index page, but redirect admins to admin panel if logged in
    if (session()->has('user_id') && session('role') === 'admin') {
        return redirect()->route('home.admin');
    }
    return view('index');
})->name('home');

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/hasil-tes', [HasilTesController::class, 'index'])->name('hasil.index');
Route::view('/faq', 'faq')->name('faq');
Route::view('/about', 'about')->name('about');

// Booking
Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

// Pembayaran
Route::get('/pembayaran', function () {
    $input = session('booking_input');
    return view('pembayaran', [
        'nama_depan' => $input['nama_depan'] ?? null,
        'nama_belakang' => $input['nama_belakang'] ?? null,
        'telepon' => $input['telepon'] ?? null,
        'email' => $input['email'] ?? null,
        'tes' => $input['tes'] ?? null,
        'cabang' => $input['cabang'] ?? null,
        'tanggal_booking' => $input['tanggal_booking'] ?? null,
        'sesi' => $input['sesi'] ?? null,
        'harga' => isset($input['tes']) ? (int) explode('|', $input['tes'])[0] : 0,
    ]);
})->name('pembayaran.show');

// Admin panel routes
Route::middleware('admin')->group(function () {
    Route::get('/admin', function () { return view('dashboard_admin'); })->name('admin.dashboard');
    Route::view('/admin/patients', 'patients_admin')->name('admin.patients');
    Route::view('/admin/tests', 'tes_admin')->name('admin.tests');
    Route::view('/admin/booking', 'booking_admin')->name('admin.booking');
});

// User home
Route::get('/home', function () { return view('home_user'); })->name('home.user');
