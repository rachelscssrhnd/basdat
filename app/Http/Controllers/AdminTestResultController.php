<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HasilTesHeader;
use App\Models\HasilTesValue;
use App\Models\Booking;
use App\Models\ParameterTes;

class AdminTestResultController extends Controller
{
    public function index(Request $request)
    {
        $headers = HasilTesHeader::with(['booking.pasien','detailHasil.parameter'])
            ->orderByDesc('hasil_id')
            ->paginate(15);

        $bookings = Booking::with('pasien')->orderByDesc('booking_id')->limit(50)->get();
        $parameters = ParameterTes::orderBy('nama_parameter')->get();

        return view('tes_admin', compact('headers', 'bookings', 'parameters'));
    }

    public function storeHeader(Request $request)
    {
        $data = $request->validate([
            'booking_id' => ['required','exists:booking,booking_id'],
        ]);

        $header = HasilTesHeader::create([
            'booking_id' => $data['booking_id'],
            'dibuat_oleh' => session('user_id'),
            'tanggal_input' => now(),
        ]);

        return back()->with('success', 'Header hasil tes dibuat');
    }

    public function updateHeader(Request $request, HasilTesHeader $header)
    {
        $data = $request->validate([
            'booking_id' => ['required','exists:booking,booking_id'],
        ]);

        $header->update($data);
        return back()->with('success', 'Header diperbarui');
    }

    public function destroyHeader(HasilTesHeader $header)
    {
        $header->delete();
        return back()->with('success', 'Header dihapus');
    }

    public function storeValue(Request $request, HasilTesHeader $header)
    {
        $data = $request->validate([
            'param_id' => ['required','exists:parameter_tes,param_id'],
            'nilai_hasil' => ['required','string','max:255'],
        ]);

        HasilTesValue::create([
            'hasil_id' => $header->hasil_id,
            'param_id' => $data['param_id'],
            'nilai_hasil' => $data['nilai_hasil'],
        ]);

        return back()->with('success', 'Nilai hasil ditambahkan');
    }

    public function updateValue(Request $request, HasilTesHeader $header, HasilTesValue $value)
    {
        $data = $request->validate([
            'param_id' => ['required','exists:parameter_tes,param_id'],
            'nilai_hasil' => ['required','string','max:255'],
        ]);
        $value->update($data);
        return back()->with('success', 'Nilai hasil diperbarui');
    }

    public function destroyValue(HasilTesHeader $header, HasilTesValue $value)
    {
        $value->delete();
        return back()->with('success', 'Nilai hasil dihapus');
    }
}


