<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\JenisTes;
use App\Models\Pasien;
use App\Models\LogActivity;
use App\Models\HasilTesHeader;
use App\Models\HasilTesValue;
use App\Models\ParameterTes;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function index()
    {
        try {
            // Get recent bookings
            $recentBookings = Booking::with(['pasien', 'cabang', 'jenisTes'])
                ->orderBy('tanggal_booking', 'desc')
                ->limit(10)
                ->get();

            // Get recent test results
            $recentResults = HasilTesHeader::with(['booking.pasien'])
                ->orderBy('tanggal_input', 'desc')
                ->limit(10)
                ->get();

            // Get statistics
            $stats = [
                'total_bookings' => Booking::count(),
                'pending_payments' => Booking::where('status_pembayaran', 'pending')->count(),
                'completed_tests' => Booking::where('status_tes', 'completed')->count(),
                'total_tests' => JenisTes::count()
            ];

            return view('admin', compact('recentBookings', 'recentResults', 'stats'));
        } catch (\Exception $e) {
            // If database is not set up, return view with sample data
            $recentBookings = collect([
                (object) [
                    'booking_id' => 'BK-001',
                    'pasien' => (object) ['nama' => 'Rachel Sunarko'],
                    'tanggal_booking' => '2025-09-23',
                    'status_pembayaran' => 'pending',
                    'status_tes' => 'scheduled'
                ],
                (object) [
                    'booking_id' => 'BK-002',
                    'pasien' => (object) ['nama' => 'John Doe'],
                    'tanggal_booking' => '2025-09-24',
                    'status_pembayaran' => 'paid',
                    'status_tes' => 'completed'
                ]
            ]);

            $recentResults = collect();
            $stats = [
                'total_bookings' => 2,
                'pending_payments' => 1,
                'completed_tests' => 1,
                'total_tests' => 3
            ];

            return view('admin', compact('recentBookings', 'recentResults', 'stats'));
        }
    }

    /**
     * Get bookings for admin management
     */
    public function getBookings()
    {
        try {
            $bookings = Booking::with(['pasien', 'cabang', 'jenisTes', 'pembayaran'])
                ->orderBy('tanggal_booking', 'desc')
                ->get();

            return response()->json($bookings);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    /**
     * Update booking status
     */
    public function updateBooking(Request $request, $id)
    {
        $validated = $request->validate([
            'status_pembayaran' => 'in:pending,paid,failed',
            'status_tes' => 'in:scheduled,in_progress,completed,cancelled'
        ]);

        try {
            $booking = Booking::findOrFail($id);
            $booking->update($validated);
            
            return response()->json(['success' => true, 'message' => 'Booking updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update booking']);
        }
    }

    /**
     * Delete booking
     */
    public function deleteBooking($id)
    {
        try {
            DB::beginTransaction();
            
            $booking = Booking::findOrFail($id);
            
            // Delete related records
            $booking->pembayaran()->delete();
            $booking->hasilTesHeader()->delete();
            $booking->jenisTes()->detach();
            $booking->delete();
            
            DB::commit();
            
            return response()->json(['success' => true, 'message' => 'Booking deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to delete booking']);
        }
    }

    /**
     * Approve (verify) a payment for a booking
     */
    public function approvePayment($id)
    {
        try {
            $booking = Booking::with('pembayaran')->findOrFail($id);
            if (!$booking->pembayaran) {
                return response()->json(['success' => false, 'message' => 'No payment found']);
            }
            $booking->pembayaran->update(['status' => 'verified']);
            return response()->json(['success' => true, 'message' => 'Payment verified']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Verification failed']);
        }
    }

    /**
     * Get lab tests for admin management
     */
    public function getTests()
    {
        try {
            $tests = JenisTes::with('parameterTes')->get();
            return response()->json($tests);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    /**
     * Parameter management endpoints
     */
    public function listParameters($testId)
    {
        try {
            $params = ParameterTes::where('tes_id', $testId)->get();
            return response()->json($params);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    public function createParameter(Request $request, $testId)
    {
        $validated = $request->validate([
            'nama_parameter' => 'required|string|max:255',
            'satuan' => 'nullable|string|max:50',
        ]);

        try {
            $param = ParameterTes::create([
                'tes_id' => $testId,
                'nama_parameter' => $validated['nama_parameter'],
                'satuan' => $validated['satuan'] ?? null,
            ]);

            LogActivity::create([
                'user_id' => session('user_id'),
                'action' => 'Created parameter: ' . $param->nama_parameter . ' for test ID ' . $testId,
                'created_at' => now(),
            ]);

            return response()->json(['success' => true, 'parameter' => $param]);
        } catch (\Exception $e) {
            return response()->json(['success' => false]);
        }
    }

    public function updateParameter(Request $request, $paramId)
    {
        $validated = $request->validate([
            'nama_parameter' => 'sometimes|required|string|max:255',
            'satuan' => 'nullable|string|max:50',
        ]);

        try {
            $param = ParameterTes::findOrFail($paramId);
            $param->update($validated);

            LogActivity::create([
                'user_id' => session('user_id'),
                'action' => 'Updated parameter: ' . $param->nama_parameter,
                'created_at' => now(),
            ]);

            return response()->json(['success' => true, 'parameter' => $param]);
        } catch (\Exception $e) {
            return response()->json(['success' => false]);
        }
    }

    public function deleteParameter($paramId)
    {
        try {
            $param = ParameterTes::findOrFail($paramId);
            $name = $param->nama_parameter;
            $param->delete();

            LogActivity::create([
                'user_id' => session('user_id'),
                'action' => 'Deleted parameter: ' . $name,
                'created_at' => now(),
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false]);
        }
    }

    /**
     * Create new lab test
     */
    public function createTest(Request $request)
    {
        $validated = $request->validate([
            'nama_tes' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'persiapan_khusus' => 'nullable|string'
        ]);

        try {
            $test = JenisTes::create($validated);

            LogActivity::create([
                'user_id' => session('user_id'),
                'action' => 'Created test: ' . $test->nama_tes,
                'created_at' => now(),
            ]);

            return response()->json(['success' => true, 'message' => 'Test created successfully', 'test' => $test]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to create test']);
        }
    }

    /**
     * Update lab test
     */
    public function updateTest(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_tes' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'persiapan_khusus' => 'nullable|string'
        ]);

        try {
            $test = JenisTes::findOrFail($id);
            $test->update($validated);

            LogActivity::create([
                'user_id' => session('user_id'),
                'action' => 'Updated test: ' . $test->nama_tes,
                'created_at' => now(),
            ]);
            
            return response()->json(['success' => true, 'message' => 'Test updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update test']);
        }
    }

    /**
     * Delete lab test
     */
    public function deleteTest($id)
    {
        try {
            $test = JenisTes::findOrFail($id);
            $name = $test->nama_tes;
            $test->delete();

            LogActivity::create([
                'user_id' => session('user_id'),
                'action' => 'Deleted test: ' . $name,
                'created_at' => now(),
            ]);
            
            return response()->json(['success' => true, 'message' => 'Test deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete test']);
        }
    }
}
