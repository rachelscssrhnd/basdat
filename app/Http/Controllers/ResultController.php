<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HasilTesHeader;
use App\Models\HasilTesValue;
use App\Models\Booking;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Models\Pasien;

class ResultController extends Controller
{
    /**
     * Display test results
     */
    public function index(Request $request)
    {
        $transactionId = $request->get('transaction_id');

        try {
            $userId = session('user_id');
            $pasien = Pasien::where('user_id', $userId)->first();
            if (!$pasien) {
                $availableResults = collect();
                return view('result', ['result' => null, 'availableResults' => $availableResults, 'error' => 'Unauthorized']);
            }

            $patientName = $pasien->nama ?? session('username') ?? '-';

            // Latest hasil_id per booking that actually has values
            $latestHeaderByBooking = DB::table('hasil_tes_header as hth')
                ->join('hasil_tes_value as htv', 'hth.hasil_id', '=', 'htv.hasil_id')
                ->select('hth.booking_id', DB::raw('MAX(hth.hasil_id) as hasil_id'))
                ->groupBy('hth.booking_id');

            // Equivalent to:
            // booking_id, nama_parameter, nilai_hasil, satuan (plus tanggal_tes & nama_tes for UI)
            $rowsQuery = DB::table('booking as b')
                ->joinSub($latestHeaderByBooking, 'lh', function ($join) {
                    $join->on('b.booking_id', '=', 'lh.booking_id');
                })
                ->join('hasil_tes_header as hth', 'lh.hasil_id', '=', 'hth.hasil_id')
                ->join('hasil_tes_value as htv', 'hth.hasil_id', '=', 'htv.hasil_id')
                ->join('parameter_tes as p', 'htv.param_id', '=', 'p.param_id')
                ->leftJoin('jenis_tes as jt', 'p.tes_id', '=', 'jt.tes_id')
                ->where('b.pasien_id', $pasien->pasien_id)
                ->select(
                    'b.booking_id',
                    'hth.tanggal_input as tanggal_tes',
                    'p.tes_id',
                    'jt.nama_tes',
                    'p.nama_parameter',
                    'htv.nilai_hasil',
                    'p.satuan'
                )
                ->orderBy('b.booking_id')
                ->orderBy('p.nama_parameter');

            if ($transactionId) {
                $rowsQuery->where('b.booking_id', $transactionId);
            }

            $rows = collect($rowsQuery->get());

            if ($transactionId && $rows->isEmpty()) {
                return view('result', [
                    'error' => null,
                    'patientName' => $patientName,
                    'resultSets' => [[
                        'transaction_id' => $transactionId,
                        'booking_id' => $transactionId,
                        'tanggal_tes' => null,
                        'tests' => [],
                        'status' => 'pending',
                        'message' => 'Hasil tes belum tersedia. Silakan cek kembali nanti.',
                    ]],
                ]);
            }

            $resultSets = [];
            $byBooking = $rows->groupBy(function ($r) {
                return (string) $r->booking_id;
            });

            foreach ($byBooking as $bookingId => $bookingRows) {
                $tanggalTes = optional($bookingRows->first())->tanggal_tes;
                $tests = [];
                $byTes = $bookingRows->groupBy(function ($r) {
                    return (string) ($r->tes_id ?? 0);
                });

                foreach ($byTes as $tesId => $tesRows) {
                    $testName = optional($tesRows->first())->nama_tes;
                    if (empty($testName)) {
                        $testName = ((string) $tesId === '0') ? 'Hasil Tes' : ('Tes #' . $tesId);
                    }

                    $params = $tesRows->map(function ($r) {
                        return [
                            'name' => $r->nama_parameter,
                            'value' => $r->nilai_hasil,
                            'unit' => $r->satuan,
                        ];
                    })->values()->all();

                    $tests[] = [
                        'tes_id' => (string) $tesId,
                        'name' => $testName,
                        'parameters' => $params,
                    ];
                }

                $resultSets[] = [
                    'transaction_id' => $bookingId,
                    'booking_id' => $bookingId,
                    'tanggal_tes' => $tanggalTes,
                    'tests' => $tests,
                ];
            }

            return view('result', [
                'error' => null,
                'patientName' => $patientName,
                'resultSets' => $resultSets,
            ]);
        } catch (\Exception $e) {
            // On exception, render empty cards rather than sample data
            return view('result', [
                'error' => 'Failed to load results',
                'patientName' => session('username') ?? '-',
                'resultSets' => [],
            ]);
        }
    }

    /**
     * Download test result as PDF
     */
    public function download($transactionId)
    {
        try {
            $userId = session('user_id');
            $pasien = Pasien::where('user_id', $userId)->first();
            if (!$pasien) {
                return redirect()->route('result')->withErrors(['error' => 'Unauthorized']);
            }

            $booking = Booking::with(['pasien', 'jenisTes'])
                ->where('booking_id', $transactionId)
                ->first();
            
            if (!$booking || $booking->pasien_id !== $pasien->pasien_id) {
                return redirect()->route('result')->withErrors(['error' => 'Transaction not found']);
            }

            // Get test results
            $hasilTes = HasilTesHeader::with(['detailHasil.parameter'])
                ->where('booking_id', $booking->booking_id)
                ->get();

            if ($hasilTes->isEmpty()) {
                return redirect()->route('result', ['transaction_id' => $transactionId])->withErrors(['error' => 'Result not available']);
            }

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
