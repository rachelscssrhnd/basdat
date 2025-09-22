<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HasilTesHeader;

class HasilTesController extends Controller
{
    public function index()
    {
        if (!session()->has('user_id')) {
            return redirect()->route('login');
        }

        try {
            $list = HasilTesHeader::query()->orderByDesc('hasil_id')->get();
        } catch (\Throwable $e) {
            $list = collect();
        }

        $role = session('role');
        if ($role === 'admin') {
            return view('hasil_test_header_admin', ['items' => $list]);
        }
        return view('hasil_test_header', ['items' => $list]);
    }
}


