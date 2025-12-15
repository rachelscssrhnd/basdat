<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LabTestController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MyOrderController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BranchController;
use Illuminate\Http\Request;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication routes
Route::get('/auth', [AuthController::class, 'showLogin'])->name('auth');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Role-based dashboard for pasien
Route::middleware(['auth.session', 'role:pasien'])->group(function () {
    Route::get('/user/home', [HomeController::class, 'index'])->name('user.home');

    // Booking routes
    Route::get('/booking', [BookingController::class, 'index'])->name('booking');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/{id}', [BookingController::class, 'show'])->name('booking.show');
    Route::put('/booking/{id}', [BookingController::class, 'update'])->name('booking.update');

    // Payment routes
    Route::get('/payment', [PaymentController::class, 'index'])->name('payment');
    Route::post('/payment/{bookingId}/upload', [PaymentController::class, 'uploadProof'])->name('payment.upload');

    // My order routes
    Route::get('/myorder', [MyOrderController::class, 'index'])->name('myorder');
    Route::get('/myorder/{id}', [MyOrderController::class, 'show'])->name('myorder.show');
    Route::get('/myorder/search', [MyOrderController::class, 'search'])->name('myorder.search');
    Route::post('/myorder/{bookingId}/upload-proof', [MyOrderController::class, 'uploadProof'])->name('myorder.upload_proof');
});

// Lab test routes (public for demo)
Route::get('/labtest', [LabTestController::class, 'index'])->name('labtest');
Route::get('/labtest/search', [LabTestController::class, 'search'])->name('labtest.search');
Route::get('/labtest/filter', [LabTestController::class, 'filter'])->name('labtest.filter');

// Result routes
Route::get('/result', [ResultController::class, 'index'])->name('result');
Route::get('/result/download/{transactionId}', [ResultController::class, 'download'])->name('result.download');

// Branch routes
Route::get('/branches', [BranchController::class, 'index'])->name('branches');
Route::get('/api/branches', [BranchController::class, 'api'])->name('branches.api');

// Admin routes
Route::middleware(['auth.session', 'isAdmin'])->group(function () {
    // Dashboard
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');

    // Admin booking management
    Route::get('/admin/bookings', [AdminController::class, 'getBookings'])->name('admin.bookings');
    Route::put('/admin/bookings/{id}', [AdminController::class, 'updateBooking'])->name('admin.bookings.update');
    Route::delete('/admin/bookings/{id}', [AdminController::class, 'deleteBooking'])->name('admin.bookings.delete');
    Route::post('/admin/bookings/{id}/approve', [AdminController::class, 'approveBooking'])->name('admin.bookings.approve');
    Route::post('/admin/bookings/{id}/reject', [AdminController::class, 'rejectBooking'])->name('admin.bookings.reject');
    Route::post('/admin/bookings/{id}/approve-payment', [AdminController::class, 'approvePayment'])->name('admin.bookings.approve_payment');

    // Admin payment management
    Route::get('/admin/payments', [AdminController::class, 'getPayments'])->name('admin.payments');
    Route::get('/admin/payments/{id}/proof', [AdminController::class, 'viewPaymentProof'])->name('admin.payments.proof');
    Route::post('/admin/payments/{id}/confirm', [AdminController::class, 'confirmPayment'])->name('admin.payments.confirm');
    Route::post('/admin/payments/{id}/reject', [AdminController::class, 'rejectPayment'])->name('admin.payments.reject');
    Route::put('/admin/payments/{id}', [AdminController::class, 'updatePayment'])->name('admin.payments.update');
    Route::delete('/admin/payments/{id}', [AdminController::class, 'deletePayment'])->name('admin.payments.delete');

    // Admin test management
    Route::get('/admin/tests', [AdminController::class, 'getTests'])->name('admin.tests');
    Route::get('/admin/tests/{id}', [AdminController::class, 'getTest'])->name('admin.tests.show');
    Route::post('/admin/tests', [AdminController::class, 'createTest'])->name('admin.tests.create');
    Route::put('/admin/tests/{id}', [AdminController::class, 'updateTest'])->name('admin.tests.update');
    Route::delete('/admin/tests/{id}', [AdminController::class, 'deleteTest'])->name('admin.tests.delete');

    // Parameters management
    Route::get('/admin/tests/{testId}/parameters', [AdminController::class, 'listParameters'])->name('admin.parameters.list');
    Route::post('/admin/tests/{testId}/parameters', [AdminController::class, 'createParameter'])->name('admin.parameters.create');
    Route::put('/admin/parameters/{paramId}', [AdminController::class, 'updateParameter'])->name('admin.parameters.update');
    Route::delete('/admin/parameters/{paramId}', [AdminController::class, 'deleteParameter'])->name('admin.parameters.delete');
});
