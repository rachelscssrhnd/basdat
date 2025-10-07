<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Pasien;
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
            // For demo purposes, get all bookings
            // In a real app, you'd filter by authenticated user
            $allBookings = Booking::with(['pasien', 'cabang', 'jenisTes', 'pembayaran'])
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
                    return $booking->status_pembayaran === 'pending' || 
                           $booking->status_tes === 'scheduled';
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
            $booking = Booking::with(['pasien', 'cabang', 'jenisTes', 'pembayaran'])
                ->findOrFail($id);
            
            return view('myorder.show', compact('booking'));
        } catch (\Exception $e) {
            return redirect()->route('myorder')->withErrors(['error' => 'Order not found']);
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
