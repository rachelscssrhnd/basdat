<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;

class AdminPatientController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');
        $pasiens = Pasien::when($q, function ($query) use ($q) {
                $query->where('nama', 'like', "%$q%")
                      ->orWhere('email', 'like', "%$q%")
                      ->orWhere('no_hp', 'like', "%$q%");
            })
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

        return view('patients_admin', compact('pasiens', 'q'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required','string','max:255'],
            'tgl_lahir' => ['nullable','date'],
            'email' => ['nullable','email','max:255'],
            'no_hp' => ['nullable','string','max:30'],
        ]);

        Pasien::create($data);
        return back()->with('success', 'Pasien ditambahkan');
    }

    public function update(Request $request, Pasien $pasien)
    {
        $data = $request->validate([
            'nama' => ['required','string','max:255'],
            'tgl_lahir' => ['nullable','date'],
            'email' => ['nullable','email','max:255'],
            'no_hp' => ['nullable','string','max:30'],
        ]);

        $pasien->update($data);
        return back()->with('success', 'Pasien diperbarui');
    }

    public function destroy(Pasien $pasien)
    {
        $pasien->delete();
        return back()->with('success', 'Pasien dihapus');
    }
}


