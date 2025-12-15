<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Pembayaran;
use App\Models\Pasien;
use App\Models\Cabang;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Display payment page
     */
    public function index(Request $request)
    {
        // Check if user is logged in
        if (!session()->has('user_id')) {
            return redirect()->route('auth')->with('error', 'Please login to access payment page.');
        }

        $bookingId = $request->get('booking_id');
        if (!$bookingId) {
            return redirect()->route('myorder')->with('error', 'Booking ID is required.');
        }

        try {
            // Get booking with related data
            $booking = Booking::with(['pasien', 'cabang', 'jenisTes', 'pembayaran'])
                ->where('booking_id', $bookingId)
                ->firstOrFail();

            // Check if booking belongs to current user
            $userId = session('user_id');
            $pasien = Pasien::where('user_id', $userId)->first();
            if (!$pasien || $booking->pasien_id !== $pasien->pasien_id) {
                return redirect()->route('myorder')->with('error', 'Unauthorized access to booking.');
            }

            // Check if payment already exists
            if (!$booking->pembayaran) {
                return redirect()->route('myorder')->with('error', 'Payment record not found.');
            }

            // Generate payment details based on method
            $paymentDetails = $this->generatePaymentDetails($booking->pembayaran);

            if (isset($booking->cabang) && $booking->cabang) {
                $branchLabelById = Cabang::orderBy('cabang_id')
                    ->pluck('cabang_id')
                    ->values()
                    ->mapWithKeys(function ($cabangId, $idx) {
                        return [$cabangId => 'Cabang ' . chr(65 + $idx)];
                    });
                $label = $branchLabelById[$booking->cabang_id] ?? null;
                if ($label) {
                    $booking->cabang->display_name = $label;
                }
            }

            $sesiNumber = $booking->sesi ?? $request->query('sesi') ?? $request->get('sesi') ?? session('booking_sesi_' . $bookingId);
            if ($sesiNumber) {
                session(['booking_sesi_' . $bookingId => $sesiNumber]);

                try {
                    \App\Models\LogActivity::create([
                        'user_id' => session('user_id'),
                        'action' => 'booking_sesi:' . $sesiNumber . ';booking_id:' . $bookingId,
                        'resource_type' => 'booking',
                        'created_at' => now(),
                    ]);
                } catch (\Exception $e) {
                    \App\Models\LogActivity::create([
                        'user_id' => session('user_id'),
                        'action' => 'booking_sesi:' . $sesiNumber . ';booking_id:' . $bookingId,
                        'created_at' => now(),
                    ]);
                }
            }

            $sesiLabel = null;
            $sesiMap = [
                1 => 'Sesi 1 (08:00-10:00)',
                2 => 'Sesi 2 (10:00-12:00)',
                3 => 'Sesi 3 (13:00-15:00)',
                4 => 'Sesi 4 (15:00-17:00)',
            ];
            if ($sesiNumber && isset($sesiMap[(int) $sesiNumber])) {
                $sesiLabel = $sesiMap[(int) $sesiNumber];
            }

            return view('payment', compact('booking', 'paymentDetails', 'sesiLabel', 'sesiNumber'));

        } catch (\Exception $e) {
            \Log::error('Payment page error', ['error' => $e->getMessage(), 'booking_id' => $bookingId]);
            return redirect()->route('myorder')->with('error', 'Payment page not found.');
        }
    }

    /**
     * Generate payment details (VA number, QR code, etc.)
     */
    private function generatePaymentDetails($pembayaran)
    {
        $details = [
            'method' => $pembayaran->metode_bayar,
            'amount' => $pembayaran->jumlah,
            'booking_id' => $pembayaran->booking_id,
        ];

        if ($pembayaran->metode_bayar === 'transfer') {
            // Generate Virtual Account number
            $details['va_number'] = 'VA-' . date('Ymd') . '-' . str_pad($pembayaran->booking_id, 6, '0', STR_PAD_LEFT);
            $details['bank_name'] = 'Bank BCA';
            $details['instructions'] = [
                '1. Transfer ke nomor Virtual Account di atas',
                '2. Jumlah yang harus ditransfer: Rp ' . number_format($pembayaran->jumlah, 0, ',', '.'),
                '3. Pastikan nominal transfer sesuai dengan tagihan',
                '4. Upload bukti transfer setelah melakukan pembayaran'
            ];
        } else {
            // E-Wallet details
            $details['ewallet_number'] = '08123456789';
            $details['ewallet_name'] = 'DANA';
            $details['qr_code'] = 'data:image/svg+xml;base64,' . base64_encode($this->generateQRCode($pembayaran->jumlah));
            $details['instructions'] = [
                '1. Buka aplikasi ' . $details['ewallet_name'],
                '2. Transfer ke nomor: ' . $details['ewallet_number'],
                '3. Jumlah yang harus ditransfer: Rp ' . number_format($pembayaran->jumlah, 0, ',', '.'),
                '4. Upload bukti transfer setelah melakukan pembayaran'
            ];
        }

        return $details;
    }

    /**
     * Generate simple QR code (dummy)
     */
    private function generateQRCode($amount)
    {
        return '<svg width="200" height="200" xmlns="http://www.w3.org/2000/svg">
            <rect width="200" height="200" fill="white"/>
            <rect x="20" y="20" width="160" height="160" fill="black"/>
            <text x="100" y="100" text-anchor="middle" fill="white" font-size="12">QR Code</text>
            <text x="100" y="120" text-anchor="middle" fill="white" font-size="10">Rp ' . number_format($amount, 0, ',', '.') . '</text>
        </svg>';
    }

    /**
     * Upload payment proof
     */
    public function uploadProof(Request $request, $bookingId)
    {
        // Check if user is logged in
        if (!session()->has('user_id')) {
            return redirect()->route('auth')->with('error', 'Please login to upload payment proof.');
        }

        $validated = $request->validate([
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
        ]);

        try {
            DB::beginTransaction();

            // Get booking
            $booking = Booking::with(['pasien', 'pembayaran'])
                ->where('booking_id', $bookingId)
                ->firstOrFail();

            // Check if booking belongs to current user
            $userId = session('user_id');
            $pasien = Pasien::where('user_id', $userId)->first();
            if (!$pasien || $booking->pasien_id !== $pasien->pasien_id) {
                return redirect()->route('myorder', ['tab' => 'current'])->with('error', 'Unauthorized access to booking.');
            }

            // Check if payment exists
            if (!$booking->pembayaran) {
                return redirect()->route('myorder', ['tab' => 'current'])->with('error', 'Payment record not found.');
            }

            // Store payment proof
            $file = $request->file('payment_proof');
            $fileName = 'payment_proof_' . $bookingId . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('payment_proofs', $fileName, 'public');

            // Update payment record
            $pembayaran = $booking->pembayaran;
            $paymentId = $pembayaran->getKey();
            $updated = false;

            try {
                Pembayaran::where('pembayaran_id', $paymentId)->update([
                    'bukti_pembayaran' => $filePath,
                    'status' => 'waiting_confirmation',
                    'tanggal_upload' => now(),
                ]);
                $updated = true;
            } catch (QueryException $qe) {
                if (stripos($qe->getMessage(), 'Unknown column') === false) {
                    throw $qe;
                }
            }

            if (!$updated) {
                try {
                    Pembayaran::where('pembayaran_id', $paymentId)->update([
                        'bukti_pembayaran' => $filePath,
                        'status' => 'waiting_confirmation',
                    ]);
                    $updated = true;
                } catch (QueryException $qe) {
                    if (stripos($qe->getMessage(), 'Unknown column') === false) {
                        throw $qe;
                    }
                }
            }

            if (!$updated) {
                try {
                    Pembayaran::where('pembayaran_id', $paymentId)->update([
                        'bukti_path' => $filePath,
                        'status' => 'waiting_confirmation',
                        'tanggal_upload' => now(),
                    ]);
                    $updated = true;
                } catch (QueryException $qe) {
                    if (stripos($qe->getMessage(), 'Unknown column') === false) {
                        throw $qe;
                    }
                }
            }

            if (!$updated) {
                try {
                    Pembayaran::where('pembayaran_id', $paymentId)->update([
                        'bukti_path' => $filePath,
                        'status' => 'waiting_confirmation',
                    ]);
                    $updated = true;
                } catch (QueryException $qe) {
                    if (stripos($qe->getMessage(), 'Unknown column') === false) {
                        throw $qe;
                    }
                }
            }

            if (!$updated) {
                Pembayaran::where('pembayaran_id', $paymentId)->update([
                    'status' => 'waiting_confirmation',
                ]);
            }

            // Update booking status
            $booking->update([
                'status_pembayaran' => 'waiting_confirmation'
            ]);

            $sesiNumber = $request->get('sesi');
            if ($sesiNumber) {
                session(['booking_sesi_' . $bookingId => $sesiNumber]);
                try {
                    \App\Models\LogActivity::create([
                        'user_id' => session('user_id'),
                        'action' => 'booking_sesi:' . $sesiNumber . ';booking_id:' . $bookingId,
                        'resource_type' => 'booking',
                        'created_at' => now(),
                    ]);
                } catch (\Exception $e) {
                    \App\Models\LogActivity::create([
                        'user_id' => session('user_id'),
                        'action' => 'booking_sesi:' . $sesiNumber . ';booking_id:' . $bookingId,
                        'created_at' => now(),
                    ]);
                }
            }

            // Log activity
            \App\Models\LogActivity::create([
                'user_id' => session('user_id'),
                'action' => 'Uploaded payment proof for booking ID: ' . $bookingId,
                'created_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('myorder', ['tab' => 'current'])->with('success', 'Bukti pembayaran berhasil diupload! Menunggu konfirmasi admin.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Payment proof upload failed', ['error' => $e->getMessage(), 'booking_id' => $bookingId]);
            return back()->with('error', 'Gagal mengupload bukti pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Confirm payment (Admin only)
     */
    public function confirmPayment(Request $request, $bookingId)
    {
        // Check if user is admin
        if (session('role_name') !== 'admin') {
            return redirect()->route('user.home')->withErrors(['error' => 'Unauthorized. Admin only.']);
        }

        $validated = $request->validate([
            'action' => 'required|in:confirm,reject',
            'reason' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $booking = Booking::with(['pembayaran', 'pasien'])->findOrFail($bookingId);
            $pembayaran = $booking->pembayaran;

            if (!$pembayaran) {
                return back()->withErrors(['error' => 'Payment record not found.']);
            }

            if ($validated['action'] === 'confirm') {
                // Confirm payment
                $pembayaran->update([
                    'status' => 'confirmed',
                    'tanggal_konfirmasi' => now(),
                ]);

                $booking->update([
                    'status_pembayaran' => 'confirmed',
                    'status_tes' => 'confirmed'
                ]);

                $message = 'Pembayaran berhasil dikonfirmasi.';
            } else {
                // Reject payment
                $pembayaran->update([
                    'status' => 'rejected',
                    'alasan_reject' => $validated['reason'],
                    'tanggal_konfirmasi' => now(),
                ]);

                $booking->update([
                    'status_pembayaran' => 'rejected'
                ]);

                $message = 'Pembayaran ditolak: ' . ($validated['reason'] ?? 'Tidak ada alasan yang diberikan.');
            }

            // Log activity
            \App\Models\LogActivity::create([
                'user_id' => session('user_id'),
                'action' => 'Payment ' . $validated['action'] . ' for booking ID: ' . $bookingId,
                'created_at' => now(),
            ]);

            DB::commit();

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Payment confirmation failed', ['error' => $e->getMessage(), 'booking_id' => $bookingId]);
            return back()->withErrors(['error' => 'Gagal memproses konfirmasi pembayaran: ' . $e->getMessage()]);
        }
    }
}
