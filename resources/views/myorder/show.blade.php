<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Clinic Lab - Order Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
</head>
<body class="font-sans antialiased text-gray-800">
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <i data-feather="activity" class="h-8 w-8 text-green-600"></i>
                        <span class="ml-2 text-2xl font-bold text-green-700">E-Clinic Lab</span>
                    </div>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                    <a href="{{ route('home') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        <i data-feather="home" class="mr-2"></i> Home
                    </a>
                    <a href="{{ route('labtest') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        <i data-feather="flask" class="mr-2"></i> Lab Test
                    </a>
                    <a href="{{ route('result') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        <i data-feather="clipboard" class="mr-2"></i> Test Result
                    </a>
                    <a href="{{ route('myorder', ['tab' => 'current']) }}" class="border-primary-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        <i data-feather="shopping-bag" class="mr-2"></i> My Order
                    </a>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                @if(session()->has('user_id'))
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700">Welcome, {{ session('username') }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-white px-4 py-2 rounded-md text-sm font-medium bg-red-500 hover:bg-red-600">
                                <i data-feather="log-out" class="mr-1"></i> Logout
                            </button>
                        </form>
                    </div>
                @else
                    <div class="flex items-center space-x-2">
                    <a href="{{ route('auth') }}" class="text-white px-4 py-2 rounded-md text-sm font-medium flex items-center bg-green-600 hover:bg-green-700">
                        <i data-feather="user" class="mr-2"></i> Sign In
                    </a>
                    </div>
                @endif
                </div>
            </div>
        </div>
    </nav>

    <div class="bg-gradient-to-r from-green-50 to-green-50 py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900">Order Details</h1>
                    <p class="mt-1 text-sm text-gray-600">Transaction ID: <span class="font-semibold">{{ $booking->booking_id }}</span></p>
                </div>
                <a href="{{ route('myorder', ['tab' => 'current']) }}" class="text-sm text-gray-600 hover:text-gray-800">
                    <i data-feather="arrow-left" class="inline mr-1"></i> Back
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center mb-4">
                    <i data-feather="calendar" class="mr-2 text-green-600"></i> Schedule
                </h2>
                @php
                    $sesiNumber = $booking->sesi ?? ($booking->sesi_fallback ?? null) ?? session('booking_sesi_' . $booking->booking_id);
                    $sesiMap = [
                        1 => 'Sesi 1 (08:00-10:00)',
                        2 => 'Sesi 2 (10:00-12:00)',
                        3 => 'Sesi 3 (13:00-15:00)',
                        4 => 'Sesi 4 (15:00-17:00)',
                    ];
                    $sesiLabel = $sesiNumber && isset($sesiMap[(int) $sesiNumber]) ? $sesiMap[(int) $sesiNumber] : null;
                @endphp
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <div class="text-gray-500">Date</div>
                        <div class="font-semibold text-gray-900">{{ $booking->tanggal_booking ? \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') : '-' }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Session</div>
                        <div class="font-semibold text-gray-900">{{ $sesiLabel ?? ('Sesi ' . ($sesiNumber ?? '-')) }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Branch</div>
                        <div class="font-semibold text-gray-900">{{ $booking->cabang->display_name ?? $booking->cabang->nama_cabang ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Patient</div>
                        <div class="font-semibold text-gray-900">{{ $booking->pasien->nama ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center mb-4">
                    <i data-feather="flask" class="mr-2 text-green-600"></i> Tests
                </h2>
                <div class="space-y-2">
                    @php
                        $subtotal = 0;
                    @endphp
                    @foreach(($booking->jenisTes ?? collect()) as $test)
                        @php
                            $subtotal += (int) ($test->harga ?? 0);
                        @endphp
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                            <div>
                                <div class="font-medium text-gray-900">{{ $test->nama_tes }}</div>
                                <div class="text-sm text-gray-500">{{ $test->deskripsi ?? '' }}</div>
                            </div>
                            <div class="font-semibold text-gray-900">Rp{{ number_format($test->harga ?? 0, 0, ',', '.') }}</div>
                        </div>
                    @endforeach

                    @if(($booking->jenisTes ?? collect())->isEmpty())
                        <div class="text-sm text-gray-500">No tests found.</div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center mb-4">
                    <i data-feather="credit-card" class="mr-2 text-green-600"></i> Payment
                </h2>

                @php
                    $serviceFee = 5000;
                    $total = ($booking->pembayaran->jumlah ?? ($subtotal + $serviceFee));
                    $method = $booking->pembayaran->metode_bayar ?? '-';
                    $payStatus = $booking->status_pembayaran ?? '-';
                    $proofPath = $booking->pembayaran->bukti_pembayaran ?? ($booking->pembayaran->bukti_path ?? null);
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <div class="text-gray-500">Payment Method</div>
                        <div class="font-semibold text-gray-900">{{ strtoupper($method) }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Payment Status</div>
                        @php
                            $payBadgeClass = 'text-gray-700 bg-gray-50 border border-gray-200';
                            $payStatusLower = strtolower((string) $payStatus);
                            if (in_array($payStatusLower, ['waiting_confirmation', 'pending', 'failed'])) {
                                $payBadgeClass = 'text-red-700 bg-red-50 border border-red-200';
                            } elseif (in_array($payStatusLower, ['verified', 'paid', 'completed'])) {
                                $payBadgeClass = 'text-green-700 bg-green-50 border border-green-200';
                            }
                        @endphp
                        <div>
                            <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold {{ $payBadgeClass }}">
                                {{ strtoupper($payStatus) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 border-t border-gray-200 pt-4 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <div class="text-gray-600">Subtotal</div>
                        <div class="font-medium">Rp{{ number_format($subtotal, 0, ',', '.') }}</div>
                    </div>
                    <div class="flex justify-between">
                        <div class="text-gray-600">Service Fee</div>
                        <div class="font-medium">Rp{{ number_format($serviceFee, 0, ',', '.') }}</div>
                    </div>
                    <div class="flex justify-between border-t border-gray-200 pt-2 font-semibold text-gray-900">
                        <div>Total</div>
                        <div>Rp{{ number_format($total, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <footer class="bg-gray-50">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="mt-12 border-t border-gray-200 pt-8">
                <p class="text-base text-gray-400 text-center">
                    &copy; 2025 E-Clinic Lab. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <script>
        AOS.init();
        feather.replace();

        @if(session('success'))
            alert('{{ session('success') }}');
        @endif

        @if(session('error'))
            alert('{{ session('error') }}');
        @endif

        @if($errors->any())
            alert('{{ $errors->first() }}');
        @endif
    </script>
</body>
</html>
