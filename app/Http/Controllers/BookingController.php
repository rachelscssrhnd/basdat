<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Pasien;
use App\Models\Cabang;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        if (!session()->has('user_id')) {
            return redirect()->route('login');
        }

        $bookings = Booking::query()
            ->with(['pasien', 'cabang'])
            ->orderByDesc('booking_id')
            ->paginate(10);

        $pasiens = Pasien::query()->orderBy('nama')->get(['pasien_id','nama']);
        $cabangs = Cabang::query()->orderBy('nama_cabang')->get(['cabang_id','nama_cabang']);

        // Get test type from query parameter
        $selectedTestType = $request->get('type', '');

        return view('booking', compact('bookings', 'pasiens', 'cabangs', 'selectedTestType'));
    }

    public function store(Request $request)
    {
        if (!session()->has('user_id')) {
            return redirect()->route('login');
        }

        $data = $request->validate([
            'pasien_id' => ['required','exists:pasien,pasien_id'],
            'cabang_id' => ['required','exists:cabang,cabang_id'],
            'tanggal_booking' => ['required','date'],
        ]);

        $data['status_pembayaran'] = 'belum_bayar';
        $data['status_tes'] = 'menunggu';

        Booking::create($data);

        return redirect()->route('booking.index')->with('success','Booking dibuat.');
    }
}


