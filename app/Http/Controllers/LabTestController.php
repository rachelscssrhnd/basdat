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
            $tests = JenisTes::with('parameterTes')->get();
            
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
            $tests = JenisTes::where('nama_tes', 'like', "%{$query}%")
                ->orWhere('deskripsi', 'like', "%{$query}%")
                ->get();
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
