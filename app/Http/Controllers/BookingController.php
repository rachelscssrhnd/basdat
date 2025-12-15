<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\JenisTes;
use App\Models\Cabang;
use App\Models\Pasien;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Display the booking form
     */
    public function index(Request $request)
    {
        try {
            // Debug log
            \Log::info('BookingController - Request Data:', [
                'test_id' => $request->get('test_id'),
                'session_data' => session()->all()
            ]);

            // If not logged in, remember intended test and redirect to login
            if (!session()->has('user_id')) {
                if ($request->has('test_id')) {
                    session(['intended_test_id' => $request->query('test_id')]);
                    \Log::info('Storing intended test ID:', ['test_id' => $request->query('test_id')]);
                }
                return redirect()->route('auth')->with('error', 'Silakan login untuk memesan tes.');
            }

            // Get available lab tests
            $tests = JenisTes::all();
            if ($tests->isEmpty()) {
                $catalog = [
                    ['harga' => 100000, 'nama_tes' => 'Tes Rontgen Gigi (Dental I CR)', 'deskripsi' => 'Pemeriksaan rontgen gigi untuk membantu mendeteksi kondisi gigi dan rahang.'],
                    ['harga' => 150000, 'nama_tes' => 'Tes Rontgen Gigi (Panoramic)', 'deskripsi' => 'Pemeriksaan rontgen menyeluruh area mulut dan rahang (panoramik).'],
                    ['harga' => 200000, 'nama_tes' => "Tes Rontgen Gigi (Water\'s Foto)", 'deskripsi' => 'Pemeriksaan rontgen untuk membantu evaluasi area sinus dan tulang wajah.'],
                    ['harga' => 50000,  'nama_tes' => 'Tes Urine', 'deskripsi' => 'Pemeriksaan urine untuk membantu deteksi infeksi, metabolisme, dan kondisi kesehatan umum.'],
                    ['harga' => 120000, 'nama_tes' => 'Tes Kehamilan (Anti-Rubella lgG)', 'deskripsi' => 'Pemeriksaan antibodi Rubella IgG untuk melihat riwayat paparan/imunitas.'],
                    ['harga' => 120000, 'nama_tes' => 'Tes Kehamilan (Anti-CMV lgG)', 'deskripsi' => 'Pemeriksaan antibodi CMV IgG untuk melihat riwayat paparan/imunitas.'],
                    ['harga' => 120000, 'nama_tes' => 'Tes Kehamilan (Anti-HSV1 lgG)', 'deskripsi' => 'Pemeriksaan antibodi HSV-1 IgG untuk melihat riwayat paparan/imunitas.'],
                    ['harga' => 75000,  'nama_tes' => 'Tes Darah (Hemoglobin)', 'deskripsi' => 'Pemeriksaan kadar hemoglobin untuk membantu evaluasi anemia dan kondisi darah.'],
                    ['harga' => 90000,  'nama_tes' => 'Tes Darah (Golongan Darah)', 'deskripsi' => 'Pemeriksaan golongan darah ABO dan Rhesus.'],
                    ['harga' => 100000, 'nama_tes' => 'Tes Darah (Agregasi Trombosit)', 'deskripsi' => 'Pemeriksaan fungsi trombosit untuk membantu evaluasi pembekuan darah.'],
                ];

                $tests = collect(array_map(function ($t, $idx) {
                    return (object) [
                        'tes_id' => $idx + 1,
                        'nama_tes' => $t['nama_tes'],
                        'deskripsi' => $t['deskripsi'] ?? ('Deskripsi ' . $t['nama_tes']),
                        'harga' => $t['harga'],
                    ];
                }, $catalog, array_keys($catalog)));
            }

            // Get available branches
            $branches = Cabang::all();
            if ($branches->isEmpty()) {
                $branches = collect([
                    (object) ['cabang_id' => 1, 'nama_cabang' => 'Cabang A', 'display_name' => 'Cabang A', 'alamat' => 'Jl. Sudirman No. 1'],
                    (object) ['cabang_id' => 2, 'nama_cabang' => 'Cabang B', 'display_name' => 'Cabang B', 'alamat' => 'Jl. Thamrin No. 2'],
                    (object) ['cabang_id' => 3, 'nama_cabang' => 'Cabang C', 'display_name' => 'Cabang C', 'alamat' => 'Jl. Gatot Subroto No. 3'],
                ]);
            } else {
                $branches = $branches->values()->map(function ($branch, $idx) {
                    $label = 'Cabang ' . chr(65 + $idx);
                    $branch->display_name = $label;
                    return $branch;
                });
            }

            // Determine selected test id
            $selectedTest = null;
            $testId = null;
            if ($request->has('test_id')) {
                $testId = (int) $request->query('test_id');
            } elseif (session()->has('intended_test_id')) {
                $testId = (int) session('intended_test_id');
            } elseif (session()->has('selected_test_id')) {
                $testId = (int) session('selected_test_id');
            }

            // Ignore invalid/zero test id
            if ($testId !== null && $testId <= 0) {
                $testId = null;
            }

            // Get the selected test
            if ($testId !== null) {
                session(['selected_test_id' => $testId]);
                session()->forget('intended_test_id');

                // Use tes_id explicitly (find() may not work if primary key is not id)
                $selectedTest = JenisTes::where('tes_id', $testId)->first();

                if (!$selectedTest) {
                    $selectedTest = $tests->firstWhere('tes_id', $testId);
                }

                \Log::info('Selected test:', [
                    'test_id' => $testId,
                    'found' => (bool) $selectedTest
                ]);
            } else {
                session()->forget('intended_test_id');
            }

            // Session options for dropdown
            $sessions = [
                '1' => 'Sesi 1 (08:00-10:00)',
                '2' => 'Sesi 2 (10:00-12:00)',
                '3' => 'Sesi 3 (13:00-15:00)',
                '4' => 'Sesi 4 (15:00-17:00)',
            ];

            return view('booking', [
                'tests' => $tests,
                'branches' => $branches,
                'selectedTest' => $selectedTest,
                'sessions' => $sessions
            ]);
        } catch (\Exception $e) {
            \Log::error('BookingController@index error: ' . $e->getMessage());

            $tests = collect([
                (object) ['tes_id' => 1, 'nama_tes' => 'Tes Rontgen Gigi (Dental I CR)', 'harga' => 100000, 'deskripsi' => 'Deskripsi Tes Rontgen Gigi (Dental I CR)'],
                (object) ['tes_id' => 2, 'nama_tes' => 'Tes Rontgen Gigi (Panoramic)', 'harga' => 150000, 'deskripsi' => 'Deskripsi Tes Rontgen Gigi (Panoramic)'],
                (object) ['tes_id' => 3, 'nama_tes' => "Tes Rontgen Gigi (Water\'s Foto)", 'harga' => 200000, 'deskripsi' => "Deskripsi Tes Rontgen Gigi (Water\'s Foto)"],
                (object) ['tes_id' => 4, 'nama_tes' => 'Tes Urine', 'harga' => 50000, 'deskripsi' => 'Deskripsi Tes Urine'],
                (object) ['tes_id' => 5, 'nama_tes' => 'Tes Kehamilan (Anti-Rubella lgG)', 'harga' => 120000, 'deskripsi' => 'Deskripsi Tes Kehamilan (Anti-Rubella lgG)'],
                (object) ['tes_id' => 6, 'nama_tes' => 'Tes Kehamilan (Anti-CMV lgG)', 'harga' => 120000, 'deskripsi' => 'Deskripsi Tes Kehamilan (Anti-CMV lgG)'],
                (object) ['tes_id' => 7, 'nama_tes' => 'Tes Kehamilan (Anti-HSV1 lgG)', 'harga' => 120000, 'deskripsi' => 'Deskripsi Tes Kehamilan (Anti-HSV1 lgG)'],
                (object) ['tes_id' => 8, 'nama_tes' => 'Tes Darah (Hemoglobin)', 'harga' => 75000, 'deskripsi' => 'Deskripsi Tes Darah (Hemoglobin)'],
                (object) ['tes_id' => 9, 'nama_tes' => 'Tes Darah (Golongan Darah)', 'harga' => 90000, 'deskripsi' => 'Deskripsi Tes Darah (Golongan Darah)'],
                (object) ['tes_id' => 10, 'nama_tes' => 'Tes Darah (Agregasi Trombosit)', 'harga' => 100000, 'deskripsi' => 'Deskripsi Tes Darah (Agregasi Trombosit)'],
            ]);

            $branches = collect([
                (object) ['cabang_id' => 1, 'nama_cabang' => 'Cabang A', 'display_name' => 'Cabang A', 'alamat' => 'Jl. Sudirman No. 1'],
                (object) ['cabang_id' => 2, 'nama_cabang' => 'Cabang B', 'display_name' => 'Cabang B', 'alamat' => 'Jl. Thamrin No. 2'],
                (object) ['cabang_id' => 3, 'nama_cabang' => 'Cabang C', 'display_name' => 'Cabang C', 'alamat' => 'Jl. Gatot Subroto No. 3'],
            ]);

            $testId = $request->has('test_id') ? (int) $request->query('test_id') : null;
            if ($testId !== null && $testId <= 0) {
                $testId = null;
            }

            $selectedTest = null;
            if ($testId !== null) {
                $selectedTest = $tests->firstWhere('tes_id', $testId);
            }

            $sessions = [
                '1' => 'Sesi 1 (08:00-10:00)',
                '2' => 'Sesi 2 (10:00-12:00)',
                '3' => 'Sesi 3 (13:00-15:00)',
                '4' => 'Sesi 4 (15:00-17:00)',
            ];

            return view('booking', [
                'tests' => $tests,
                'branches' => $branches,
                'selectedTest' => $selectedTest,
                'sessions' => $sessions
            ]);
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

            $hasSesiColumn = Schema::hasColumn('booking', 'sesi');

            // Use authenticated session user as patient
            $userId = session('user_id');
            $pasien = Pasien::where('user_id', $userId)->firstOrFail();

            // Anti-collision rule: Check if session is full
            $existingBookingsQuery = Booking::where('tanggal_booking', $validated['tanggal_booking'])
                ->where('cabang_id', $validated['cabang_id'])
                ->whereIn('status_tes', ['pending_approval', 'approved', 'confirmed']);

            if ($hasSesiColumn) {
                $existingBookingsQuery->where('sesi', $validated['sesi']);
            }

            $existingBookings = $existingBookingsQuery->count();

            // Assuming max 5 bookings per session (adjust as needed)
            $maxBookingsPerSession = 5;
            if ($existingBookings >= $maxBookingsPerSession) {
                DB::rollBack();
                return back()->withErrors(['error' => 'Jadwal sudah penuh, silakan pilih sesi lain.'])->withInput();
            }

            // Create booking with session
            $bookingData = [
                'pasien_id' => $pasien->pasien_id,
                'cabang_id' => $validated['cabang_id'],
                'tanggal_booking' => $validated['tanggal_booking'],
                'status_pembayaran' => 'pending',
                'status_tes' => 'pending_approval'
            ];

            if ($hasSesiColumn) {
                $bookingData['sesi'] = $validated['sesi'];
            }

            $booking = Booking::create($bookingData);

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
                'action' => 'Created booking ID: ' . $booking->booking_id . ($hasSesiColumn ? (' for session ' . $validated['sesi']) : ''),
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
            
            return view('myorder.show', compact('booking'));
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