<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Pasien;
use App\Models\Cabang;

class AdminBookingController extends Controller
{
    public function index(Request $request)
    {
        $bookings = Booking::with(['pasien','cabang'])
            ->orderByDesc('booking_id')
            ->paginate(15);

        return view('booking_admin', compact('bookings'));
    }

    public function update(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'status_pembayaran' => ['nullable','in:belum_bayar,menunggu_konfirmasi,terbayar'],
            'status_tes' => ['nullable','in:menunggu,dijadwalkan,selesai,dibatalkan'],
            'tanggal_booking' => ['nullable','date'],
        ]);

        $booking->fill(array_filter($data, fn($v) => $v !== null && $v !== ''));
        $booking->save();

        return back()->with('success', 'Booking updated');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return back()->with('success', 'Booking deleted');
    }
}


