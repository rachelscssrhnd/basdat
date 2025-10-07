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

class BookingController extends Controller
{
    /**
     * Display the booking form
     */
    public function index(Request $request)
    {
        try {
            // Get available lab tests
            $tests = JenisTes::all();
            
            // Get available branches
            // Ensure branches include Cabang A/B/C as defaults
            $branches = Cabang::all();
            if ($branches->isEmpty()) {
                $branches = collect([
                    (object) ['cabang_id' => 1, 'nama_cabang' => 'Cabang A'],
                    (object) ['cabang_id' => 2, 'nama_cabang' => 'Cabang B'],
                    (object) ['cabang_id' => 3, 'nama_cabang' => 'Cabang C'],
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

            return view('booking', compact('tests', 'branches', 'selectedTests'));
        } catch (\Exception $e) {
            // If database is not set up, return view with sample data
            $tests = collect([
                (object) ['tes_id' => 1, 'nama_tes' => 'Basic Health Panel', 'harga' => 89000],
                (object) ['tes_id' => 2, 'nama_tes' => 'Complete Metabolic Panel', 'harga' => 129000],
                (object) ['tes_id' => 3, 'nama_tes' => 'Immunity Checkup', 'harga' => 75000]
            ]);
            
            $branches = collect([
                (object) ['cabang_id' => 1, 'nama_cabang' => 'Jakarta Central', 'alamat' => 'Jl. Sudirman No. 123'],
                (object) ['cabang_id' => 2, 'nama_cabang' => 'Jakarta Selatan', 'alamat' => 'Jl. Pondok Indah No. 456']
            ]);
            
            return view('booking', compact('tests', 'branches'));
        }
    }

    /**
     * Store a new booking
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_booking' => 'required|date|after:today',
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

            // Create booking
            $booking = Booking::create([
                'pasien_id' => $pasien->pasien_id,
                'cabang_id' => $validated['cabang_id'],
                'tanggal_booking' => $validated['tanggal_booking'],
                'status_pembayaran' => 'pending',
                'status_tes' => 'pending_approval'
            ]);

            // Attach selected tests to booking
            $booking->jenisTes()->attach($validated['tes_ids']);

            // Create payment record
            $totalHarga = JenisTes::whereIn('tes_id', $validated['tes_ids'])->sum('harga');
            $pembayaran = Pembayaran::create([
                'booking_id' => $booking->booking_id,
                'metode_bayar' => $validated['payment_method'],
                'jumlah' => $totalHarga,
                'status' => 'pending',
                'tanggal_bayar' => now()
            ]);

            \App\Models\LogActivity::create([
                'user_id' => session('user_id'),
                'action' => 'Created booking ID: ' . $booking->booking_id,
                'created_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('myorder')->with('success', 'Booking successful! Transaction ID: ' . $booking->booking_id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to create booking: ' . $e->getMessage()])->withInput();
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