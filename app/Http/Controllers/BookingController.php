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
            'pasien_id' => ['nullable','exists:pasien,pasien_id'],
            'cabang_id' => ['nullable','exists:cabang,cabang_id'],
            'tanggal_booking' => ['required','date'],
            'nama_depan' => ['nullable','string'],
            'nama_belakang' => ['nullable','string'],
            'telepon' => ['nullable','string'],
            'email' => ['nullable','string'],
            'tes' => ['nullable','string'],
            'sesi' => ['nullable','string'],
        ]);

        $data['status_pembayaran'] = 'belum_bayar';
        $data['status_tes'] = 'menunggu';

        // Simpan jika referensi pasien/cabang tersedia
        if (!empty($data['pasien_id']) && !empty($data['cabang_id'])) {
            Booking::create([
                'pasien_id' => $data['pasien_id'],
                'cabang_id' => $data['cabang_id'],
                'tanggal_booking' => $data['tanggal_booking'],
                'status_pembayaran' => $data['status_pembayaran'],
                'status_tes' => $data['status_tes'],
            ]);
        }

        // Redirect ke halaman pembayaran dan tampilkan data input
        return redirect()->route('pembayaran.show')->with('booking_input', $data);
    }
}


