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

// Example admin-only routes
Route::middleware('admin')->group(function () {
    Route::get('/admin/hasil-tes', [HasilTesController::class, 'index'])->name('admin.hasil.index');
    Route::get('/admin', function () { return view('home_admin'); })->name('home.admin');
});

// User home
Route::get('/home', function () { return view('home_user'); })->name('home.user');
