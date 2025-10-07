<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisTes;
use App\Models\ParameterTes;

class LabTestController extends Controller
{
    /**
     * Display the lab test catalog page
     */
    public function index()
    {
        try {
            // Get all available lab tests with their parameters
            $allowed = [
                'Tes Rontgen Gigi (Dental I CR)',
                'Tes Rontgen Gigi (Panoramic)',
                "Tes Rontgen Gigi (Water's Foto)",
                'Tes Urine',
                'Tes Kehamilan (Anti-Rubella lgG)',
                'Tes Kehamilan (Anti-CMV lgG)',
                'Tes Kehamilan (Anti-HSV1 lgG)',
                'Tes Darah (Hemoglobin)',
                'Tes Darah (Golongan Darah)',
                'Tes Darah (Agregasi Trombosit)'
            ];
            $tests = JenisTes::with('parameterTes')
                ->whereIn('nama_tes', $allowed)
                ->orderBy('tes_id')
                ->get();

            // Fallback: if DB has none, build from catalog in-memory
            if ($tests->isEmpty()) {
                $catalog = [
                    ['harga' => 100000, 'nama_tes' => 'Tes Rontgen Gigi (Dental I CR)'],
                    ['harga' => 150000, 'nama_tes' => 'Tes Rontgen Gigi (Panoramic)'],
                    ['harga' => 200000, 'nama_tes' => "Tes Rontgen Gigi (Water's Foto)"],
                    ['harga' => 50000,  'nama_tes' => 'Tes Urine'],
                    ['harga' => 120000, 'nama_tes' => 'Tes Kehamilan (Anti-Rubella lgG)'],
                    ['harga' => 120000, 'nama_tes' => 'Tes Kehamilan (Anti-CMV lgG)'],
                    ['harga' => 120000, 'nama_tes' => 'Tes Kehamilan (Anti-HSV1 lgG)'],
                    ['harga' => 75000,  'nama_tes' => 'Tes Darah (Hemoglobin)'],
                    ['harga' => 90000,  'nama_tes' => 'Tes Darah (Golongan Darah)'],
                    ['harga' => 100000, 'nama_tes' => 'Tes Darah (Agregasi Trombosit)'],
                ];
                $tests = collect(array_map(function($t) {
                    return (object) [
                        'tes_id' => 0,
                        'nama_tes' => $t['nama_tes'],
                        'deskripsi' => 'Deskripsi ' . $t['nama_tes'],
                        'harga' => $t['harga'],
                        'parameter_tes' => collect(),
                    ];
                }, $catalog));
            }
            
            return view('labtest', compact('tests'));
        } catch (\Exception $e) {
            // If database is not set up, return view with sample data
            $tests = collect([
                (object) [
                    'tes_id' => 1,
                    'nama_tes' => 'Basic Health Panel',
                    'deskripsi' => '12 tests included',
                    'harga' => 89000,
                    'persiapan_khusus' => 'Fasting required',
                    'parameter_tes' => collect()
                ],
                (object) [
                    'tes_id' => 2,
                    'nama_tes' => 'Complete Metabolic Panel',
                    'deskripsi' => '20 tests included',
                    'harga' => 129000,
                    'persiapan_khusus' => 'Fasting required',
                    'parameter_tes' => collect()
                ],
                (object) [
                    'tes_id' => 3,
                    'nama_tes' => 'Immunity Checkup',
                    'deskripsi' => '8 tests included',
                    'harga' => 75000,
                    'persiapan_khusus' => 'No special preparation',
                    'parameter_tes' => collect()
                ]
            ]);
            
            return view('labtest', compact('tests'));
        }
    }

    /**
     * Search lab tests
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        try {
            $allowed = [
                'Tes Rontgen Gigi (Dental I CR)',
                'Tes Rontgen Gigi (Panoramic)',
                "Tes Rontgen Gigi (Water's Foto)",
                'Tes Urine',
                'Tes Kehamilan (Anti-Rubella lgG)',
                'Tes Kehamilan (Anti-CMV lgG)',
                'Tes Kehamilan (Anti-HSV1 lgG)',
                'Tes Darah (Hemoglobin)',
                'Tes Darah (Golongan Darah)',
                'Tes Darah (Agregasi Trombosit)'
            ];
            $tests = JenisTes::whereIn('nama_tes', $allowed)
                ->where(function($q) use ($query) {
                    $q->where('nama_tes', 'like', "%{$query}%")
                ->orWhere('deskripsi', 'like', "%{$query}%")
                ;})
                ->orderBy('tes_id')
                ->get();

            if ($tests->isEmpty()) {
                $catalog = [
                    ['harga' => 100000, 'nama_tes' => 'Tes Rontgen Gigi (Dental I CR)'],
                    ['harga' => 150000, 'nama_tes' => 'Tes Rontgen Gigi (Panoramic)'],
                    ['harga' => 200000, 'nama_tes' => "Tes Rontgen Gigi (Water's Foto)"],
                    ['harga' => 50000,  'nama_tes' => 'Tes Urine'],
                    ['harga' => 120000, 'nama_tes' => 'Tes Kehamilan (Anti-Rubella lgG)'],
                    ['harga' => 120000, 'nama_tes' => 'Tes Kehamilan (Anti-CMV lgG)'],
                    ['harga' => 120000, 'nama_tes' => 'Tes Kehamilan (Anti-HSV1 lgG)'],
                    ['harga' => 75000,  'nama_tes' => 'Tes Darah (Hemoglobin)'],
                    ['harga' => 90000,  'nama_tes' => 'Tes Darah (Golongan Darah)'],
                    ['harga' => 100000, 'nama_tes' => 'Tes Darah (Agregasi Trombosit)'],
                ];
                $tests = collect(array_values(array_filter(array_map(function($t) use ($query) {
                    if ($query && stripos($t['nama_tes'], $query) === false) return null;
                    return [
                        'tes_id' => 0,
                        'nama_tes' => $t['nama_tes'],
                        'deskripsi' => 'Deskripsi ' . $t['nama_tes'],
                        'harga' => $t['harga'],
                    ];
                }, $catalog))));
            }
        } catch (\Exception $e) {
            $tests = collect();
        }
        
        return response()->json($tests);
    }

    /**
     * Filter lab tests by price range
     */
    public function filter(Request $request)
    {
        $minPrice = $request->get('min_price', 0);
        $maxPrice = $request->get('max_price', 999999);
        
        try {
            $tests = JenisTes::whereBetween('harga', [$minPrice, $maxPrice])
                ->get();
        } catch (\Exception $e) {
            $tests = collect();
        }
        
        return response()->json($tests);
    }
}
