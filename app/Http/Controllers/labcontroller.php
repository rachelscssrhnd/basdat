<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LabController extends Controller
{
    // Halaman daftar lab test
    public function indexLabTest()
    {
        // Contoh data lab test (bisa diganti ambil dari DB)
        $tests = [
            [
                'name' => 'Basic Health Panel',
                'icon' => 'heart',
                'price' => 89000,
                'original_price' => 120000,
                'discount' => 25,
                'items' => 12
            ],
            [
                'name' => 'Complete Metabolic Panel',
                'icon' => 'activity',
                'price' => 129000,
                'original_price' => 180000,
                'discount' => 28,
                'items' => 20
            ],
            [
                'name' => 'Immunity Checkup',
                'icon' => 'shield',
                'price' => 75000,
                'original_price' => 100000,
                'discount' => 25,
                'items' => 8
            ],
            // Tambahkan test lainnya
        ];

        return view('labtest', compact('tests'));
    }

    // Halaman daftar order user
    public function myOrder()
    {
        // Contoh data order user
        $orders = [
            [
                'transaction_id' => 'LTNW0033250923000005',
                'date' => '23 Sep 2025',
                'patient_name' => 'Rachel Sunarko',
                'items_count' => 1,
                'total_price' => 85000,
                'status' => 'WAITING FOR PAYMENT'
            ],
            // Tambahkan order lainnya
        ];

        return view('myorder', compact('orders'));
    }

    // Halaman hasil lab test
    public function result($transactionId)
    {
        // Contoh data hasil test
        $result = [
            'patient_name' => 'John Doe',
            'transaction_id' => $transactionId,
            'tests' => [
                [
                    'name' => 'Basic Health Panel',
                    'status' => 'Completed',
                    'parameters' => [
                        ['name' => 'Hemoglobin', 'value' => '14.1 g/dL', 'range' => '13.5 - 17.5', 'flag' => 'Normal'],
                        ['name' => 'WBC', 'value' => '6.8 ×10^3/µL', 'range' => '4.0 - 11.0', 'flag' => 'Normal'],
                        ['name' => 'Fasting Glucose', 'value' => '112 mg/dL', 'range' => '70 - 99', 'flag' => 'Slightly High'],
                        ['name' => 'Cholesterol (Total)', 'value' => '185 mg/dL', 'range' => '125 - 200', 'flag' => 'Normal'],
                    ]
                ],
                // Tambahkan test lainnya jika ada
            ]
        ];

        return view('result', compact('result'));
    }
}
