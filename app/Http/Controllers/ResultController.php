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
        
        try {
            if ($transactionId) {
                // Find booking by transaction ID
                $booking = Booking::with(['pasien', 'jenisTes'])
                    ->where('booking_id', $transactionId)
                    ->first();
                
                if (!$booking) {
                    return view('result', ['result' => null, 'error' => 'Transaction not found']);
                }

                // Enforce: only show results if payment verified and results exist
                $isVerified = optional($booking->pembayaran)->status === 'verified';
                if (!$isVerified) {
                    return view('result', ['result' => ['tests' => []], 'error' => null]);
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
                // No transaction provided → render empty cards
                $result = ['tests' => []];
            }

            return view('result', compact('result'));
        } catch (\Exception $e) {
            // On exception, render empty cards rather than sample data
            $result = ['tests' => []];

            return view('result', compact('result'));
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
