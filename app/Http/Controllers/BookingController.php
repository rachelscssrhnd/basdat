<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\JenisTes;
use App\Models\Cabang;
use App\Models\Pasien;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Display the booking form
     */
    public function index(Request $request)
    {
        // Check if user is logged in
        if (!session()->has('user_id')) {
            return redirect()->route('auth')->withErrors(['error' => 'Please login to book a test.']);
        }

        try {
            // Get available lab tests
            $tests = JenisTes::all();
            
            // Get available branches (Cabang A, B, C)
            $branches = Cabang::all();
            if ($branches->isEmpty()) {
                $branches = collect([
                    (object) ['cabang_id' => 1, 'nama_cabang' => 'Cabang A', 'alamat' => 'Jl. Sudirman No. 1'],
                    (object) ['cabang_id' => 2, 'nama_cabang' => 'Cabang B', 'alamat' => 'Jl. Thamrin No. 2'],
                    (object) ['cabang_id' => 3, 'nama_cabang' => 'Cabang C', 'alamat' => 'Jl. Gatot Subroto No. 3'],
                ]);
            }
            
            // Preselected test via query param
            $selectedTests = collect();
            $testId = $request->get('test_id');
            if ($testId) {
                $pre = JenisTes::where('tes_id', $testId)->get();
                if ($pre->isNotEmpty()) {
                    $selectedTests = $pre;
                }
            }

            // Session options for dropdown
            $sessions = [
                '1' => 'Sesi 1 (08:00-10:00)',
                '2' => 'Sesi 2 (10:00-12:00)',
                '3' => 'Sesi 3 (13:00-15:00)',
                '4' => 'Sesi 4 (15:00-17:00)',
            ];

            return view('booking', compact('tests', 'branches', 'selectedTests', 'sessions'));
        } catch (\Exception $e) {
            // If database is not set up, return view with sample data
            $tests = collect([
                (object) ['tes_id' => 1, 'nama_tes' => 'Tes Rontgen Gigi (Dental I CR)', 'harga' => 100000, 'deskripsi' => 'Pemeriksaan rontgen gigi dengan teknologi CR'],
                (object) ['tes_id' => 2, 'nama_tes' => 'Tes Rontgen Gigi (Panoramic)', 'harga' => 150000, 'deskripsi' => 'Pemeriksaan rontgen menyeluruh area mulut dan rahang'],
                (object) ['tes_id' => 3, 'nama_tes' => 'Tes Darah (Hemoglobin)', 'harga' => 75000, 'deskripsi' => 'Pemeriksaan kadar hemoglobin dalam darah']
            ]);
            
            $branches = collect([
                (object) ['cabang_id' => 1, 'nama_cabang' => 'Cabang A', 'alamat' => 'Jl. Sudirman No. 1'],
                (object) ['cabang_id' => 2, 'nama_cabang' => 'Cabang B', 'alamat' => 'Jl. Thamrin No. 2'],
                (object) ['cabang_id' => 3, 'nama_cabang' => 'Cabang C', 'alamat' => 'Jl. Gatot Subroto No. 3'],
            ]);

            // Preselected test via query param (fallback)
            $selectedTests = collect();
            $testId = $request->get('test_id');
            if ($testId) {
                $pre = $tests->where('tes_id', $testId);
                if ($pre->isNotEmpty()) {
                    $selectedTests = $pre;
                }
            }

            $sessions = [
                '1' => 'Sesi 1 (08:00-10:00)',
                '2' => 'Sesi 2 (10:00-12:00)',
                '3' => 'Sesi 3 (13:00-15:00)',
                '4' => 'Sesi 4 (15:00-17:00)',
            ];
            
            return view('booking', compact('tests', 'branches', 'selectedTests', 'sessions'));
        }
    }

    /**
     * Store a new booking
     */
    public function store(Request $request)
    {
        // Check if user is logged in
        if (!session()->has('user_id')) {
            return redirect()->route('auth')->withErrors(['error' => 'Please login to book a test.']);
        }

        // Date validation: H+1 to +30 days
        $minDate = Carbon::tomorrow()->format('Y-m-d');
        $maxDate = Carbon::now()->addDays(30)->format('Y-m-d');

        $validated = $request->validate([
            'tanggal_booking' => "required|date|after_or_equal:{$minDate}|before_or_equal:{$maxDate}",
            'sesi' => 'required|in:1,2,3,4',
            'cabang_id' => 'required|integer',
            'tes_ids' => 'required|array',
            'tes_ids.*' => 'exists:jenis_tes,tes_id',
            'payment_method' => 'required|in:ewallet,transfer'
        ]);

        try {
            DB::beginTransaction();

            // Use authenticated session user as patient
            $userId = session('user_id');
            $pasien = Pasien::where('user_id', $userId)->firstOrFail();

            // Anti-collision rule: Check if session is full
            $existingBookings = Booking::where('tanggal_booking', $validated['tanggal_booking'])
                ->where('cabang_id', $validated['cabang_id'])
                ->where('sesi', $validated['sesi'])
                ->whereIn('status_tes', ['pending_approval', 'approved', 'confirmed'])
                ->count();

            // Assuming max 5 bookings per session (adjust as needed)
            $maxBookingsPerSession = 5;
            if ($existingBookings >= $maxBookingsPerSession) {
                DB::rollBack();
                return back()->withErrors(['error' => 'Jadwal sudah penuh, silakan pilih sesi lain.'])->withInput();
            }

            // Create booking with session
            $booking = Booking::create([
                'pasien_id' => $pasien->pasien_id,
                'cabang_id' => $validated['cabang_id'],
                'tanggal_booking' => $validated['tanggal_booking'],
                'sesi' => $validated['sesi'],
                'status_pembayaran' => 'pending',
                'status_tes' => 'pending_approval'
            ]);

            // Attach selected tests to booking
            $booking->jenisTes()->attach($validated['tes_ids']);

            // Create payment record
            $totalHarga = JenisTes::whereIn('tes_id', $validated['tes_ids'])->sum('harga');
            $serviceFee = 5000;
            $totalAmount = $totalHarga + $serviceFee;

            $pembayaran = Pembayaran::create([
                'booking_id' => $booking->booking_id,
                'metode_bayar' => $validated['payment_method'],
                'jumlah' => $totalAmount,
                'status' => 'pending',
                'tanggal_bayar' => now()
            ]);

            // Log activity
            \App\Models\LogActivity::create([
                'user_id' => session('user_id'),
                'action' => 'Created booking ID: ' . $booking->booking_id . ' for session ' . $validated['sesi'],
                'created_at' => now(),
            ]);

            DB::commit();

            // Redirect to payment page
            return redirect()->route('payment', ['booking_id' => $booking->booking_id])
                ->with('success', 'Booking berhasil! Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Booking creation failed', ['error' => $e->getMessage(), 'user_id' => session('user_id'), 'trace' => $e->getTraceAsString()]);
            return back()->withErrors(['error' => 'Gagal membuat booking: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Show booking details
     */
    public function show($id)
    {
        try {
            $booking = Booking::with(['pasien', 'cabang', 'jenisTes', 'pembayaran'])
                ->findOrFail($id);
            
            return view('booking.show', compact('booking'));
        } catch (\Exception $e) {
            return redirect()->route('myorder')->withErrors(['error' => 'Booking not found']);
        }
    }

    /**
     * Update booking status
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status_pembayaran' => 'in:pending,paid,failed',
            'status_tes' => 'in:scheduled,in_progress,completed,cancelled'
        ]);

        try {
            $booking = Booking::findOrFail($id);
            $booking->update($validated);
            
            return back()->with('success', 'Booking updated successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update booking']);
        }
    }
}