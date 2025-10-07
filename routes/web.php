<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LabTestController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\MyOrderController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BranchController;
use Illuminate\Support\Facades\Storage;
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

// Lab test routes
Route::get('/labtest', [LabTestController::class, 'index'])->name('labtest');
Route::get('/labtest/search', [LabTestController::class, 'search'])->name('labtest.search');
Route::get('/labtest/filter', [LabTestController::class, 'filter'])->name('labtest.filter');

// Booking routes
Route::get('/booking', [BookingController::class, 'index'])->name('booking');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/{id}', [BookingController::class, 'show'])->name('booking.show');
Route::put('/booking/{id}', [BookingController::class, 'update'])->name('booking.update');

// My order routes
Route::get('/myorder', [MyOrderController::class, 'index'])->name('myorder');
Route::get('/myorder/{id}', [MyOrderController::class, 'show'])->name('myorder.show');
Route::get('/myorder/search', [MyOrderController::class, 'search'])->name('myorder.search');
Route::post('/myorder/{bookingId}/upload-proof', function(Request $request, $bookingId) {
    $validated = $request->validate([
        'proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120'
    ]);
    try {
        $booking = \App\Models\Booking::with('pembayaran')->findOrFail($bookingId);
        if (!$booking->pembayaran) {
            abort(404);
        }
        $path = $request->file('proof')->store('payment_proofs', 'public');
        $booking->pembayaran->update([
            'bukti_path' => $path,
            'status' => 'pending',
        ]);
        return back()->with('success', 'Payment proof uploaded. Awaiting verification.');
    } catch (\Exception $e) {
        return back()->withErrors(['error' => 'Failed to upload proof']);
    }
})->name('myorder.upload_proof');

// Result routes
Route::get('/result', [ResultController::class, 'index'])->name('result');
Route::get('/result/download/{transactionId}', [ResultController::class, 'download'])->name('result.download');

// Branch routes
Route::get('/branches', [BranchController::class, 'index'])->name('branches');

// Admin routes
Route::middleware('admin')->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // Admin booking management
    Route::get('/admin/bookings', [AdminController::class, 'getBookings'])->name('admin.bookings');
    Route::put('/admin/bookings/{id}', [AdminController::class, 'updateBooking'])->name('admin.bookings.update');
    Route::delete('/admin/bookings/{id}', [AdminController::class, 'deleteBooking'])->name('admin.bookings.delete');
    Route::post('/admin/bookings/{id}/approve', [AdminController::class, 'approveBooking'])->name('admin.bookings.approve');
    Route::post('/admin/bookings/{id}/approve-payment', [AdminController::class, 'approvePayment'])->name('admin.bookings.approve_payment');
    
    // Admin test management
    Route::get('/admin/tests', [AdminController::class, 'getTests'])->name('admin.tests');
    Route::post('/admin/tests', [AdminController::class, 'createTest'])->name('admin.tests.create');
    Route::put('/admin/tests/{id}', [AdminController::class, 'updateTest'])->name('admin.tests.update');
    Route::delete('/admin/tests/{id}', [AdminController::class, 'deleteTest'])->name('admin.tests.delete');
    
    // Parameters management
    Route::get('/admin/tests/{testId}/parameters', [AdminController::class, 'listParameters'])->name('admin.parameters.list');
    Route::post('/admin/tests/{testId}/parameters', [AdminController::class, 'createParameter'])->name('admin.parameters.create');
    Route::put('/admin/parameters/{paramId}', [AdminController::class, 'updateParameter'])->name('admin.parameters.update');
    Route::delete('/admin/parameters/{paramId}', [AdminController::class, 'deleteParameter'])->name('admin.parameters.delete');
});
