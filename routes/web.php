<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HasilTesController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminBookingController;
use App\Http\Controllers\AdminPatientController;
use App\Http\Controllers\AdminTestResultController;

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
    // Patients CRUD
    Route::get('/admin/patients', [AdminPatientController::class, 'index'])->name('admin.patients');
    Route::post('/admin/patients', [AdminPatientController::class, 'store'])->name('admin.patients.store');
    Route::put('/admin/patients/{pasien}', [AdminPatientController::class, 'update'])->name('admin.patients.update');
    Route::delete('/admin/patients/{pasien}', [AdminPatientController::class, 'destroy'])->name('admin.patients.destroy');

    // Bookings management
    Route::get('/admin/booking', [AdminBookingController::class, 'index'])->name('admin.booking');
    Route::put('/admin/booking/{booking}', [AdminBookingController::class, 'update'])->name('admin.booking.update');
    Route::delete('/admin/booking/{booking}', [AdminBookingController::class, 'destroy'])->name('admin.booking.destroy');

    // Test results CRUD
    Route::get('/admin/tests', [AdminTestResultController::class, 'index'])->name('admin.tests');
    Route::post('/admin/tests', [AdminTestResultController::class, 'storeHeader'])->name('admin.tests.header.store');
    Route::put('/admin/tests/{header}', [AdminTestResultController::class, 'updateHeader'])->name('admin.tests.header.update');
    Route::delete('/admin/tests/{header}', [AdminTestResultController::class, 'destroyHeader'])->name('admin.tests.header.destroy');

    Route::post('/admin/tests/{header}/values', [AdminTestResultController::class, 'storeValue'])->name('admin.tests.value.store');
    Route::put('/admin/tests/{header}/values/{value}', [AdminTestResultController::class, 'updateValue'])->name('admin.tests.value.update');
    Route::delete('/admin/tests/{header}/values/{value}', [AdminTestResultController::class, 'destroyValue'])->name('admin.tests.value.destroy');
});

// User home
Route::get('/home', function () { return view('home_user'); })->name('home.user');
