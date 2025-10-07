<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cabang;

class BranchController extends Controller
{
    /**
     * Display branch locations with interactive map
     */
    public function index()
    {
        try {
            // Get all branches
            $branches = Cabang::all();
            
            // If no branches in database, create sample data
            if ($branches->isEmpty()) {
                $branches = collect([
                    (object) [
                        'cabang_id' => 1,
                        'nama_cabang' => 'Jakarta Central',
                        'alamat' => 'Jl. Sudirman No. 123, Jakarta Pusat',
                        'jam_operasional' => 'Mon-Fri: 8AM-6PM, Sat: 8AM-2PM',
                        'no_telepon' => '+62 21 1234 5678'
                    ],
                    (object) [
                        'cabang_id' => 2,
                        'nama_cabang' => 'Jakarta Selatan',
                        'alamat' => 'Jl. Pondok Indah No. 456, Jakarta Selatan',
                        'jam_operasional' => 'Mon-Fri: 8AM-6PM, Sat: 8AM-2PM',
                        'no_telepon' => '+62 21 2345 6789'
                    ],
                    (object) [
                        'cabang_id' => 3,
                        'nama_cabang' => 'Jakarta Utara',
                        'alamat' => 'Jl. Kelapa Gading No. 789, Jakarta Utara',
                        'jam_operasional' => 'Mon-Fri: 8AM-6PM, Sat: 8AM-2PM',
                        'no_telepon' => '+62 21 3456 7890'
                    ]
                ]);
            }
            
            return view('branches', compact('branches'));
            
        } catch (\Exception $e) {
            // Fallback to sample data if database error
            $branches = collect([
                (object) [
                    'cabang_id' => 1,
                    'nama_cabang' => 'Jakarta Central',
                    'alamat' => 'Jl. Sudirman No. 123, Jakarta Pusat',
                    'jam_operasional' => 'Mon-Fri: 8AM-6PM, Sat: 8AM-2PM',
                    'no_telepon' => '+62 21 1234 5678'
                ],
                (object) [
                    'cabang_id' => 2,
                    'nama_cabang' => 'Jakarta Selatan',
                    'alamat' => 'Jl. Pondok Indah No. 456, Jakarta Selatan',
                    'jam_operasional' => 'Mon-Fri: 8AM-6PM, Sat: 8AM-2PM',
                    'no_telepon' => '+62 21 2345 6789'
                ],
                (object) [
                    'cabang_id' => 3,
                    'nama_cabang' => 'Jakarta Utara',
                    'alamat' => 'Jl. Kelapa Gading No. 789, Jakarta Utara',
                    'jam_operasional' => 'Mon-Fri: 8AM-6PM, Sat: 8AM-2PM',
                    'no_telepon' => '+62 21 3456 7890'
                ]
            ]);
            
            return view('branches', compact('branches'));
        }
    }
}
