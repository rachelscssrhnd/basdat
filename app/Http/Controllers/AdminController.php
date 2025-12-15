<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\JenisTes;
use App\Models\Pasien;
use App\Models\Pembayaran;
use App\Models\LogActivity;
use App\Models\HasilTesHeader;
use App\Models\HasilTesValue;
use App\Models\ParameterTes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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

  
    public function updateBooking(Request $request, $id)
    {
        $validated = $request->validate([
            'tanggal_booking' => 'sometimes|nullable|date',
            'sesi' => 'sometimes|nullable|string|max:50',
            'status_pembayaran' => 'sometimes|nullable|in:belum_bayar,pending,waiting_confirmation,paid,confirmed,rejected,failed',
            'status_tes' => 'sometimes|nullable|in:menunggu,pending_approval,scheduled,approved,in_progress,completed,cancelled,confirmed,rejected'
        ]);

        try {
            $booking = Booking::findOrFail($id);

            if (!Schema::hasColumn('booking', 'sesi')) {
                unset($validated['sesi']);
            }
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
            DB::beginTransaction();
            
            $booking = Booking::with('pembayaran')->findOrFail($id);
            if (!$booking->pembayaran) {
                return response()->json(['success' => false, 'message' => 'No payment found']);
            }
            
            // Update payment status
            $booking->pembayaran->update([
                'status' => 'confirmed',
                'tanggal_konfirmasi' => now(),
            ]);
            
            // Update booking payment status
            $booking->update(['status_pembayaran' => 'confirmed']);
            
            // Log activity
            \App\Models\LogActivity::create([
                'user_id' => session('user_id'),
                'action' => 'Payment verified for booking ID: ' . $booking->booking_id,
                'created_at' => now(),
            ]);
            
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Payment verified successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Verification failed: ' . $e->getMessage()]);
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
     * Get single test for editing
     */
    public function getTest($id)
    {
        try {
            $test = JenisTes::findOrFail($id);
            return response()->json(['success' => true, 'data' => $test]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Test not found']);
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

    /**
     * Get all bookings for admin
     */
    public function getBookings(Request $request)
    {
        try {
            $status = $request->get('status', 'all');
            
            $query = Booking::with(['pasien', 'cabang', 'jenisTes', 'pembayaran']);
            
            if ($status !== 'all') {
                $query->where('status_tes', $status);
            }
            
            $bookings = $query->orderBy('tanggal_booking', 'desc')->get();
            
            return response()->json(['success' => true, 'data' => $bookings]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to fetch bookings']);
        }
    }

    /**
     * Approve booking
     */
    public function approveBooking($id)
    {
        try {
            DB::beginTransaction();
            
            $booking = Booking::findOrFail($id);
            $booking->update([
                'status_tes' => 'approved'
            ]);

            LogActivity::create([
                'user_id' => session('user_id'),
                'action' => 'Approved booking ID: ' . $id,
                'created_at' => now(),
            ]);

            DB::commit();
            
            return response()->json(['success' => true, 'message' => 'Booking approved successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to approve booking']);
        }
    }

    /**
     * Reject booking
     */
    public function rejectBooking(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'reason' => 'required|string|max:500'
            ]);

            DB::beginTransaction();
            
            $booking = Booking::findOrFail($id);
            $booking->update([
                'status_tes' => 'rejected',
                'alasan_reject' => $validated['reason']
            ]);

            LogActivity::create([
                'user_id' => session('user_id'),
                'action' => 'Rejected booking ID: ' . $id . ' - Reason: ' . $validated['reason'],
                'created_at' => now(),
            ]);

            DB::commit();
            
            return response()->json(['success' => true, 'message' => 'Booking rejected successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to reject booking']);
        }
    }

    /**
     * Get all payments for admin
     */
    public function getPayments(Request $request)
    {
        try {
            $status = $request->get('status', 'all');
            
            $query = Pembayaran::with(['booking.pasien', 'booking.cabang', 'booking.jenisTes']);
            
            if ($status !== 'all') {
                $query->where('status', $status);
            }
            
            $payments = $query->orderBy('tanggal_bayar', 'desc')->get();
            
            return response()->json(['success' => true, 'data' => $payments]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to fetch payments']);
        }
    }

    /**
     * View payment proof
     */
    public function viewPaymentProof($id)
    {
        try {
            $payment = Pembayaran::with(['booking.pasien'])->findOrFail($id);

            $proofPath = $payment->bukti_pembayaran ?? ($payment->bukti_path ?? null);
            
            return response()->json([
                'success' => true, 
                'data' => [
                    'payment' => $payment,
                    'proof_url' => $proofPath ? asset('storage/' . $proofPath) : null
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to fetch payment proof']);
        }
    }

    /**
     * Confirm payment
     */
    public function confirmPayment($id)
    {
        try {
            DB::beginTransaction();
            
            $payment = Pembayaran::with('booking')->findOrFail($id);
            
            $payment->update([
                'status' => 'confirmed',
                'tanggal_konfirmasi' => now()
            ]);

            $payment->booking->update([
                'status_pembayaran' => 'confirmed',
                'status_tes' => 'confirmed'
            ]);

            LogActivity::create([
                'user_id' => session('user_id'),
                'action' => 'Confirmed payment for booking ID: ' . $payment->booking_id,
                'created_at' => now(),
            ]);

            DB::commit();
            
            return response()->json(['success' => true, 'message' => 'Payment confirmed successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to confirm payment']);
        }
    }

    /**
     * Reject payment
     */
    public function rejectPayment(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'reason' => 'required|string|max:500'
            ]);

            DB::beginTransaction();
            
            $payment = Pembayaran::with('booking')->findOrFail($id);
            
            $payment->update([
                'status' => 'rejected',
                'alasan_reject' => $validated['reason'],
                'tanggal_konfirmasi' => now()
            ]);

            $payment->booking->update([
                'status_pembayaran' => 'rejected'
            ]);

            LogActivity::create([
                'user_id' => session('user_id'),
                'action' => 'Rejected payment for booking ID: ' . $payment->booking_id . ' - Reason: ' . $validated['reason'],
                'created_at' => now(),
            ]);

            DB::commit();
            
            return response()->json(['success' => true, 'message' => 'Payment rejected successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to reject payment']);
        }
    }

    public function updatePayment(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'sometimes|nullable|string',
            'metode_bayar' => 'sometimes|nullable|string',
            'jumlah' => 'sometimes|numeric|min:0',
            'tanggal_bayar' => 'sometimes|nullable|date',
            'alasan_reject' => 'sometimes|nullable|string|max:500',
        ]);

        try {
            $payment = Pembayaran::findOrFail($id);
            $payment->update($validated);

            LogActivity::create([
                'user_id' => session('user_id'),
                'action' => 'Updated payment ID: ' . $id,
                'created_at' => now(),
            ]);

            return response()->json(['success' => true, 'message' => 'Payment updated successfully']);
        } catch (
            \Exception $e
        ) {
            return response()->json(['success' => false, 'message' => 'Failed to update payment']);
        }
    }

    public function deletePayment($id)
    {
        try {
            $payment = Pembayaran::findOrFail($id);
            $payment->delete();

            LogActivity::create([
                'user_id' => session('user_id'),
                'action' => 'Deleted payment ID: ' . $id,
                'created_at' => now(),
            ]);

            return response()->json(['success' => true, 'message' => 'Payment deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete payment']);
        }
    }
}
