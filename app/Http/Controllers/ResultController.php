<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HasilTesHeader;
use App\Models\HasilTesValue;
use App\Models\Booking;
use App\Models\ParameterTes;
use Illuminate\Support\Facades\View;

class ResultController extends Controller
{
    /**
     * Display test results
     */
    public function index(Request $request)
    {
        $transactionId = $request->get('transaction_id');
        $patientName = session('username') ?? 'John Doe';
        $summary = [
            'patient' => $patientName,
            'items' => [
                ['test_name' => 'Tes Darah (Hemoglobin)', 'tanggal_tes' => now()->subDays(1)->toDateString(), 'transaction_id' => 'LTNW0033250923000005'],
                ['test_name' => 'Tes Urine', 'tanggal_tes' => now()->subDays(7)->toDateString(), 'transaction_id' => 'LTNW0033250923000004'],
                ['test_name' => 'Tes Darah (Golongan Darah)', 'tanggal_tes' => now()->subDays(14)->toDateString(), 'transaction_id' => 'LTNW0033250923000003'],
                ['test_name' => "Tes Rontgen Gigi (Water\'s Foto)", 'tanggal_tes' => now()->subDays(21)->toDateString(), 'transaction_id' => 'LTNW0033250923000002'],
                ['test_name' => 'Tes Kehamilan (Anti-CMV IgG)', 'tanggal_tes' => now()->subDays(30)->toDateString(), 'transaction_id' => 'LTNW0033250923000001'],
            ],
        ];
        
        try {
            if ($transactionId) {
                // Find booking by transaction ID
                $booking = Booking::with(['pasien', 'jenisTes'])
                    ->where('booking_id', $transactionId)
                    ->first();
                
                if (!$booking) {
                    return view('result', ['result' => null, 'summary' => $summary, 'error' => 'Transaction not found']);
                }

                $patientName = data_get($booking, 'pasien.nama') ?? $patientName;
                $summary['patient'] = $patientName;
                array_unshift($summary['items'], [
                    'test_name' => data_get(($booking->jenisTes ?? collect())->first(), 'nama_tes') ?? 'Lab Test',
                    'tanggal_tes' => now()->toDateString(),
                    'transaction_id' => (string) $booking->booking_id,
                ]);
                $summary['items'] = array_slice($summary['items'], 0, 5);

                // Enforce: only show results if payment verified and results exist
                $isVerified = optional($booking->pembayaran)->status === 'verified';
                if (!$isVerified) {
                    return view('result', ['result' => ['tests' => []], 'summary' => $summary, 'error' => null]);
                }

                // Load all headers and their values for this booking
                $headers = HasilTesHeader::with(['detailHasil.parameter'])
                    ->where('booking_id', $booking->booking_id)
                    ->get();

                // Flatten values and group by their parameter's tes_id to derive jenis tes grouping
                $allValues = collect();
                foreach ($headers as $h) {
                    foreach ($h->detailHasil as $v) {
                        $allValues->push($v);
                    }
                }

                // Build tests array grouped by jenis tes
                $tests = [];
                $jenisTes = $booking->jenisTes ?? collect();
                foreach ($jenisTes as $jt) {
                    $params = $allValues->filter(function($v) use ($jt) {
                        return optional($v->parameter)->tes_id === $jt->tes_id;
                    })->map(function($v) {
                        return [
                            'name' => optional($v->parameter)->nama_parameter,
                            'value' => $v->nilai_hasil,
                        ];
                    })->values();
                    $tests[] = [
                        'name' => $jt->nama_tes,
                        'parameters' => $params,
                    ];
                }

                // Use first header date if available
                $tanggalTes = optional($headers->first())->tanggal_input ?? optional($headers->first())->tanggal_tes;

                $result = [
                    'transaction_id' => $transactionId,
                    'booking_id' => $booking->booking_id,
                    'tanggal_tes' => $tanggalTes,
                    'tests' => $tests,
                ];
            } else {
                // No transaction provided → render demo results
                $result = [
                    'transaction_id' => 'LTNW0033250923000005',
                    'booking_id' => 'LTNW0033250923000005',
                    'tanggal_tes' => now()->toDateString(),
                    'tests' => [
                        [
                            'name' => 'Tes Darah (Hemoglobin)',
                            'tanggal_tes' => now()->subDays(1)->toDateString(),
                            'transaction_id' => 'LTNW0033250923000005',
                            'parameters' => [
                                ['name' => 'Hemoglobin', 'value' => '13.6 g/dL', 'range' => '12.0 - 16.0', 'flag' => 'Normal'],
                                ['name' => 'Hematokrit', 'value' => '40 %', 'range' => '36 - 46', 'flag' => 'Normal'],
                            ],
                        ],
                        [
                            'name' => 'Tes Urine',
                            'tanggal_tes' => now()->subDays(7)->toDateString(),
                            'transaction_id' => 'LTNW0033250923000004',
                            'parameters' => [
                                ['name' => 'pH', 'value' => '6.0', 'range' => '5.0 - 8.0', 'flag' => 'Normal'],
                                ['name' => 'Protein', 'value' => 'Negative', 'range' => 'Negative', 'flag' => 'Normal'],
                            ],
                        ],
                        [
                            'name' => 'Tes Darah (Golongan Darah)',
                            'tanggal_tes' => now()->subDays(14)->toDateString(),
                            'transaction_id' => 'LTNW0033250923000003',
                            'parameters' => [
                                ['name' => 'ABO', 'value' => 'O', 'range' => 'A/B/AB/O', 'flag' => 'Normal'],
                                ['name' => 'Rhesus', 'value' => '+', 'range' => '+ / -', 'flag' => 'Normal'],
                            ],
                        ],
                        [
                            'name' => 'Tes Kehamilan (Anti-CMV IgG)',
                            'tanggal_tes' => now()->subDays(30)->toDateString(),
                            'transaction_id' => 'LTNW0033250923000001',
                            'parameters' => [
                                ['name' => 'CMV IgG', 'value' => '180 AU/mL', 'range' => '< 10', 'flag' => 'Slightly High'],
                                ['name' => 'Interpretasi', 'value' => 'Reaktif', 'range' => 'Non-reaktif', 'flag' => 'Slightly High'],
                            ],
                        ],
                        [
                            'name' => 'Tes Darah (Agregasi Trombosit)',
                            'tanggal_tes' => now()->subDays(21)->toDateString(),
                            'transaction_id' => 'LTNW0033250923000002',
                            'parameters' => [
                                ['name' => 'Agregasi ADP', 'value' => '55 %', 'range' => '50 - 80', 'flag' => 'Normal'],
                                ['name' => 'Agregasi Epinefrin', 'value' => '45 %', 'range' => '50 - 80', 'flag' => 'High'],
                            ],
                        ],
                    ],
                ];
            }

            return view('result', ['result' => $result, 'summary' => $summary]);
        } catch (\Exception $e) {
            // On exception, render empty cards rather than sample data
            $result = ['tests' => []];

            return view('result', ['result' => $result, 'summary' => $summary]);
        }
    }

    /**
     * Download test result as PDF
     */
    public function download($transactionId)
    {
        try {
            $booking = Booking::with(['pasien', 'jenisTes'])
                ->where('booking_id', $transactionId)
                ->first();
            
            if (!$booking) {
                return redirect()->route('result')->withErrors(['error' => 'Transaction not found']);
            }

            // Get test results
            $hasilTes = HasilTesHeader::with(['detailHasil.parameter'])
                ->where('booking_id', $booking->booking_id)
                ->get();

            // Render HTML and convert to PDF using dompdf (barryvdh/laravel-dompdf)
            $html = View::make('pdf.result', [
                'booking' => $booking,
                'hasilTes' => $hasilTes
            ])->render();

            $pdf = \PDF::loadHTML($html);
            return $pdf->download('result-' . $transactionId . '.pdf');
        } catch (\Exception $e) {
            return redirect()->route('result')->withErrors(['error' => 'Failed to download result']);
        }
    }
}
