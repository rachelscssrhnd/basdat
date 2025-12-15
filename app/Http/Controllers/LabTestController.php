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
                    ['harga' => 100000, 'nama_tes' => 'Tes Rontgen Gigi (Dental I CR)', 'deskripsi' => 'Pemeriksaan rontgen gigi untuk membantu mendeteksi kondisi gigi dan rahang.'],
                    ['harga' => 150000, 'nama_tes' => 'Tes Rontgen Gigi (Panoramic)', 'deskripsi' => 'Pemeriksaan rontgen menyeluruh area mulut dan rahang (panoramik).'],
                    ['harga' => 200000, 'nama_tes' => "Tes Rontgen Gigi (Water's Foto)", 'deskripsi' => 'Pemeriksaan rontgen untuk membantu evaluasi area sinus dan tulang wajah.'],
                    ['harga' => 50000,  'nama_tes' => 'Tes Urine', 'deskripsi' => 'Pemeriksaan urine untuk membantu deteksi infeksi, metabolisme, dan kondisi kesehatan umum.'],
                    ['harga' => 120000, 'nama_tes' => 'Tes Kehamilan (Anti-Rubella lgG)', 'deskripsi' => 'Pemeriksaan antibodi Rubella IgG untuk melihat riwayat paparan/imunitas.'],
                    ['harga' => 120000, 'nama_tes' => 'Tes Kehamilan (Anti-CMV lgG)', 'deskripsi' => 'Pemeriksaan antibodi CMV IgG untuk melihat riwayat paparan/imunitas.'],
                    ['harga' => 120000, 'nama_tes' => 'Tes Kehamilan (Anti-HSV1 lgG)', 'deskripsi' => 'Pemeriksaan antibodi HSV-1 IgG untuk melihat riwayat paparan/imunitas.'],
                    ['harga' => 75000,  'nama_tes' => 'Tes Darah (Hemoglobin)', 'deskripsi' => 'Pemeriksaan kadar hemoglobin untuk membantu evaluasi anemia dan kondisi darah.'],
                    ['harga' => 90000,  'nama_tes' => 'Tes Darah (Golongan Darah)', 'deskripsi' => 'Pemeriksaan golongan darah ABO dan Rhesus.'],
                    ['harga' => 100000, 'nama_tes' => 'Tes Darah (Agregasi Trombosit)', 'deskripsi' => 'Pemeriksaan fungsi trombosit untuk membantu evaluasi pembekuan darah.'],
                ];

                $tests = collect(array_map(function($t, $idx) {
                    return (object) [
                        'tes_id' => $idx + 1,
                        'nama_tes' => $t['nama_tes'],
                        'deskripsi' => $t['deskripsi'] ?? ('Deskripsi ' . $t['nama_tes']),
                        'harga' => $t['harga'],
                        'parameter_tes' => collect(),
                    ];
                }, $catalog, array_keys($catalog)));
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
                    ['harga' => 100000, 'nama_tes' => 'Tes Rontgen Gigi (Dental I CR)', 'deskripsi' => 'Pemeriksaan rontgen gigi untuk membantu mendeteksi kondisi gigi dan rahang.'],
                    ['harga' => 150000, 'nama_tes' => 'Tes Rontgen Gigi (Panoramic)', 'deskripsi' => 'Pemeriksaan rontgen menyeluruh area mulut dan rahang (panoramik).'],
                    ['harga' => 200000, 'nama_tes' => "Tes Rontgen Gigi (Water\'s Foto)", 'deskripsi' => 'Pemeriksaan rontgen untuk membantu evaluasi area sinus dan tulang wajah.'],
                    ['harga' => 50000,  'nama_tes' => 'Tes Urine', 'deskripsi' => 'Pemeriksaan urine untuk membantu deteksi infeksi, metabolisme, dan kondisi kesehatan umum.'],
                    ['harga' => 120000, 'nama_tes' => 'Tes Kehamilan (Anti-Rubella lgG)', 'deskripsi' => 'Pemeriksaan antibodi Rubella IgG untuk melihat riwayat paparan/imunitas.'],
                    ['harga' => 120000, 'nama_tes' => 'Tes Kehamilan (Anti-CMV lgG)', 'deskripsi' => 'Pemeriksaan antibodi CMV IgG untuk melihat riwayat paparan/imunitas.'],
                    ['harga' => 120000, 'nama_tes' => 'Tes Kehamilan (Anti-HSV1 lgG)', 'deskripsi' => 'Pemeriksaan antibodi HSV-1 IgG untuk melihat riwayat paparan/imunitas.'],
                    ['harga' => 75000,  'nama_tes' => 'Tes Darah (Hemoglobin)', 'deskripsi' => 'Pemeriksaan kadar hemoglobin untuk membantu evaluasi anemia dan kondisi darah.'],
                    ['harga' => 90000,  'nama_tes' => 'Tes Darah (Golongan Darah)', 'deskripsi' => 'Pemeriksaan golongan darah ABO dan Rhesus.'],
                    ['harga' => 100000, 'nama_tes' => 'Tes Darah (Agregasi Trombosit)', 'deskripsi' => 'Pemeriksaan fungsi trombosit untuk membantu evaluasi pembekuan darah.'],
                ];
                $tests = collect(array_values(array_filter(array_map(function($t, $idx) use ($query) {
                    if ($query && stripos($t['nama_tes'], $query) === false) return null;
                    return [
                        'tes_id' => $idx + 1,
                        'nama_tes' => $t['nama_tes'],
                        'deskripsi' => $t['deskripsi'] ?? ('Deskripsi ' . $t['nama_tes']),
                        'harga' => $t['harga'],
                    ];
                }, $catalog, array_keys($catalog)))));
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

            if ($tests->isEmpty()) {
                $catalog = [
                    ['harga' => 100000, 'nama_tes' => 'Tes Rontgen Gigi (Dental I CR)', 'deskripsi' => 'Pemeriksaan rontgen gigi untuk membantu mendeteksi kondisi gigi dan rahang.'],
                    ['harga' => 150000, 'nama_tes' => 'Tes Rontgen Gigi (Panoramic)', 'deskripsi' => 'Pemeriksaan rontgen menyeluruh area mulut dan rahang (panoramik).'],
                    ['harga' => 200000, 'nama_tes' => "Tes Rontgen Gigi (Water\'s Foto)", 'deskripsi' => 'Pemeriksaan rontgen untuk membantu evaluasi area sinus dan tulang wajah.'],
                    ['harga' => 50000,  'nama_tes' => 'Tes Urine', 'deskripsi' => 'Pemeriksaan urine untuk membantu deteksi infeksi, metabolisme, dan kondisi kesehatan umum.'],
                    ['harga' => 120000, 'nama_tes' => 'Tes Kehamilan (Anti-Rubella lgG)', 'deskripsi' => 'Pemeriksaan antibodi Rubella IgG untuk melihat riwayat paparan/imunitas.'],
                    ['harga' => 120000, 'nama_tes' => 'Tes Kehamilan (Anti-CMV lgG)', 'deskripsi' => 'Pemeriksaan antibodi CMV IgG untuk melihat riwayat paparan/imunitas.'],
                    ['harga' => 120000, 'nama_tes' => 'Tes Kehamilan (Anti-HSV1 lgG)', 'deskripsi' => 'Pemeriksaan antibodi HSV-1 IgG untuk melihat riwayat paparan/imunitas.'],
                    ['harga' => 75000,  'nama_tes' => 'Tes Darah (Hemoglobin)', 'deskripsi' => 'Pemeriksaan kadar hemoglobin untuk membantu evaluasi anemia dan kondisi darah.'],
                    ['harga' => 90000,  'nama_tes' => 'Tes Darah (Golongan Darah)', 'deskripsi' => 'Pemeriksaan golongan darah ABO dan Rhesus.'],
                    ['harga' => 100000, 'nama_tes' => 'Tes Darah (Agregasi Trombosit)', 'deskripsi' => 'Pemeriksaan fungsi trombosit untuk membantu evaluasi pembekuan darah.'],
                ];
                $tests = collect(array_values(array_filter(array_map(function($t, $idx) use ($minPrice, $maxPrice) {
                    if ($t['harga'] < $minPrice || $t['harga'] > $maxPrice) return null;
                    return [
                        'tes_id' => $idx + 1,
                        'nama_tes' => $t['nama_tes'],
                        'deskripsi' => $t['deskripsi'] ?? ('Deskripsi ' . $t['nama_tes']),
                        'harga' => $t['harga'],
                    ];
                }, $catalog, array_keys($catalog)))));
            }
        } catch (\Exception $e) {
            $tests = collect();
        }
        
        return response()->json($tests);
    }
}
