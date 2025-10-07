<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Test Result</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #111; }
        h1 { font-size: 20px; margin-bottom: 0; }
        .meta { color: #555; font-size: 12px; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ddd; padding: 8px; font-size: 12px; }
        th { background: #f3f4f6; text-align: left; }
    </style>
    </head>
<body>
    <h1>E-Clinic Lab - Test Result</h1>
    <div class="meta">
        Patient: {{ $booking->pasien->nama }}<br>
        Transaction: {{ $booking->booking_id }}<br>
        Date: {{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }}
    </div>

    @foreach($hasilTes as $header)
        <h3>Panel</h3>
        <table>
            <thead>
                <tr>
                    <th>Parameter</th>
                    <th>Value</th>
                    <th>Unit</th>
                </tr>
            </thead>
            <tbody>
                @foreach($header->detailHasil as $value)
                <tr>
                    <td>{{ $value->parameter->nama_parameter ?? 'Parameter' }}</td>
                    <td>{{ $value->nilai_hasil }}</td>
                    <td>{{ $value->parameter->satuan ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</body>
</html>


