<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisTes;
use App\Models\Cabang;

class HomeController extends Controller
{
    /**
     * Display the home page with featured lab tests and clinic information
     */
    public function index()
    {
        try {
            // Get featured lab tests (first 3 tests)
            $featuredTests = JenisTes::limit(3)->get();
            
            // Get clinic branches
            $branches = Cabang::all();
            
            return view('index', compact('featuredTests', 'branches'));
        } catch (\Exception $e) {
            // If database is not set up, return view with empty data
            return view('index', [
                'featuredTests' => collect(),
                'branches' => collect()
            ]);
        }
    }
}
