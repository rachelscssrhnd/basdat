<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Pasien;
use App\Models\LogActivity;
use App\Models\Cabang;
use Illuminate\Support\Facades\Auth;

class MyOrderController extends Controller
{
    /**
     * Display user's orders
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'current');
        
        try {
            $userId = session('user_id');
            $allBookings = Booking::with(['pasien', 'cabang', 'jenisTes', 'pembayaran'])
                ->whereHas('pasien', function($q) use ($userId) {
                    $q->where('user_id', $userId);
                })
                ->orderBy('tanggal_booking', 'desc')
                ->get();
                
            // Filter bookings based on tab
            if ($tab === 'history') {
                $bookings = $allBookings->filter(function($booking) {
                    return in_array($booking->status_pembayaran, ['paid', 'completed']) || 
                           in_array($booking->status_tes, ['completed', 'cancelled']);
                });
            } else {
                $bookings = $allBookings->filter(function($booking) {
                    return in_array($booking->status_pembayaran, ['pending', 'waiting_confirmation']) || 
                           $booking->status_tes === 'scheduled';
                });
            }

            $bookingIds = $bookings->pluck('booking_id')->filter()->values();
            $bookingIdStrings = $bookingIds->map(function ($id) {
                return (string) $id;
            });

            $branchLabelById = Cabang::orderBy('cabang_id')
                ->pluck('cabang_id')
                ->values()
                ->mapWithKeys(function ($cabangId, $idx) {
                    return [$cabangId => 'Cabang ' . chr(65 + $idx)];
                });

            $bookings->each(function ($booking) use ($branchLabelById) {
                if (isset($booking->cabang) && $booking->cabang) {
                    $label = $branchLabelById[$booking->cabang_id] ?? null;
                    if ($label) {
                        $booking->cabang->display_name = $label;
                    }
                }
            });

            if ($bookingIds->isNotEmpty()) {
                $logs = LogActivity::where('action', 'like', 'booking_sesi:%')
                    ->orderBy('created_at', 'desc')
                    ->get();

                $sesiByBooking = collect();
                foreach ($logs as $log) {
                    $action = (string) $log->action;
                    $sesi = null;
                    $bookingId = null;

                    if (preg_match('/booking_sesi\s*:\s*(\d+)/i', $action, $m)) {
                        $sesi = (int) $m[1];
                    }
                    if (preg_match('/booking_id\s*:\s*([0-9]+)/i', $action, $m2)) {
                        $bookingId = (string) $m2[1];
                    }

                    if ($bookingId === null) {
                        continue;
                    }

                    if (!$bookingIdStrings->contains($bookingId)) {
                        continue;
                    }

                    // Keep first (newest) log per booking id
                    if (!$sesiByBooking->has($bookingId)) {
                        $sesiByBooking->put($bookingId, $sesi);
                    }
                }

                $bookings->each(function ($booking) use ($sesiByBooking) {
                    if (!isset($booking->sesi) || $booking->sesi === null) {
                        $booking->sesi_fallback = $sesiByBooking[(string) $booking->booking_id] ?? null;
                    }
                });
            }

            return view('myorder', compact('bookings', 'tab'));
        } catch (\Exception $e) {
            // If database is not set up, return view with sample data
            $allBookings = collect([
                (object) [
                    'booking_id' => 'LTNW0033250923000001',
                    'pasien' => (object) ['nama' => 'Rachel Sunarko'],
                    'tanggal_booking' => '2025-09-23',
                    'cabang' => (object) ['nama_cabang' => 'Jakarta Central'],
                    'status_pembayaran' => 'pending',
                    'status_tes' => 'scheduled',
                    'pembayaran' => (object) ['jumlah' => 85000],
                    'jenis_tes' => collect([
                        (object) ['nama_tes' => 'Basic Health Panel', 'harga' => 85000]
                    ])
                ],
                (object) [
                    'booking_id' => 'LTNW0033250923000002',
                    'pasien' => (object) ['nama' => 'John Doe'],
                    'tanggal_booking' => '2025-09-20',
                    'cabang' => (object) ['nama_cabang' => 'Jakarta Selatan'],
                    'status_pembayaran' => 'paid',
                    'status_tes' => 'completed',
                    'pembayaran' => (object) ['jumlah' => 129000],
                    'jenis_tes' => collect([
                        (object) ['nama_tes' => 'Complete Metabolic Panel', 'harga' => 129000]
                    ])
                ],
                (object) [
                    'booking_id' => 'LTNW0033250923000003',
                    'pasien' => (object) ['nama' => 'Jane Smith'],
                    'tanggal_booking' => '2025-09-18',
                    'cabang' => (object) ['nama_cabang' => 'Jakarta Utara'],
                    'status_pembayaran' => 'paid',
                    'status_tes' => 'completed',
                    'pembayaran' => (object) ['jumlah' => 75000],
                    'jenis_tes' => collect([
                        (object) ['nama_tes' => 'Immunity Checkup', 'harga' => 75000]
                    ])
                ]
            ]);
            
            // Filter bookings based on tab
            if ($tab === 'history') {
                $bookings = $allBookings->filter(function($booking) {
                    return in_array($booking->status_pembayaran, ['paid']) || 
                           in_array($booking->status_tes, ['completed']);
                });
            } else {
                $bookings = $allBookings->filter(function($booking) {
                    return $booking->status_pembayaran === 'pending' || 
                           $booking->status_tes === 'scheduled';
                });
            }

            return view('myorder', compact('bookings', 'tab'));
        }
    }

    /**
     * Show specific order details
     */
    public function show($id)
    {
        try {
            $userId = session('user_id');
            if (!$userId) {
                return redirect()->route('auth')->with('error', 'Please login to view order details.');
            }

            $booking = Booking::with(['pasien', 'cabang', 'jenisTes', 'pembayaran'])
                ->where('booking_id', $id)
                ->whereHas('pasien', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                })
                ->firstOrFail();

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

            if (!isset($booking->sesi) || $booking->sesi === null) {
                $targetId = (string) $booking->booking_id;
                $logs = LogActivity::where('action', 'like', 'booking_sesi:%')
                    ->orderBy('created_at', 'desc')
                    ->get();

                foreach ($logs as $log) {
                    $action = (string) $log->action;
                    $sesi = null;
                    $bookingId = null;

                    if (preg_match('/booking_sesi\s*:\s*(\d+)/i', $action, $m)) {
                        $sesi = (int) $m[1];
                    }
                    if (preg_match('/booking_id\s*:\s*([0-9]+)/i', $action, $m2)) {
                        $bookingId = (string) $m2[1];
                    }

                    if ($bookingId === $targetId) {
                        $booking->sesi_fallback = $sesi;
                        break;
                    }
                }
            }

            return view('myorder.show', compact('booking'));
        } catch (\Exception $e) {
            return redirect()->route('myorder', ['tab' => 'current'])->with('error', 'Order not found');
        }
    }

    /**
     * Search orders
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        try {
            $bookings = Booking::with(['pasien', 'cabang', 'jenisTes', 'pembayaran'])
                ->whereHas('pasien', function($q) use ($query) {
                    $q->where('nama', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%");
                })
                ->orWhere('booking_id', 'like', "%{$query}%")
                ->get();
        } catch (\Exception $e) {
            $bookings = collect();
        }
        
        return response()->json($bookings);
    }
}
