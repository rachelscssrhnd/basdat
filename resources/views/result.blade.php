<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Clinic Lab - Test Result</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
</head>
<body class="font-sans antialiased text-gray-800">
    <!-- Navigation Bar -->
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
                    <a href="{{ route('result') }}" class="border-primary-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        <i data-feather="clipboard" class="mr-2"></i> Test Result
                    </a>
                    <a href="{{ route('myorder') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
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
                    <a href="{{ route('auth') }}" class="text-white px-4 py-2 rounded-md text-sm font-medium flex items-center bg-gradient-to-r from-green-500 to-yellow-400 hover:from-green-600 hover:to-yellow-500">
                        <i data-feather="user" class="mr-2"></i> Sign In
                    </a>
                    </div>
                @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <div class="bg-white py-5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-extrabold text-gray-900 flex items-center"><i data-feather="clipboard" class="mr-3 text-green-600"></i> Test Result Summary</h1>
            <p class="mt-2 text-gray-600">Patient: {{ $patientName ?? (session('username') ?? '-') }}</p>
        </div>
    </div>

    <!-- Results -->
    <div class="bg-white py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <?php if (!empty($error)) : ?>
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-feather="alert-triangle" class="h-12 w-12 text-red-600"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-900 mb-4">Error</h3>
                    <p class="text-lg text-gray-600 mb-6">{{ $error }}</p>
                </div>
            <?php else : ?>
                <?php $resultSets = (array) ($resultSets ?? []); ?>
                <?php if (count($resultSets) === 0) : ?>
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i data-feather="clipboard" class="h-12 w-12 text-gray-500"></i>
                        </div>
                        <h3 class="text-2xl font-semibold text-gray-900 mb-4">No Results Yet</h3>
                        <p class="text-lg text-gray-600 mb-6">Belum ada hasil tes yang tersedia untuk akun kamu.</p>
                    </div>
                <?php else : ?>
                    <?php foreach ($resultSets as $set) : ?>
                        <?php
                            $txnId = data_get($set, 'transaction_id');
                            $tanggalTes = data_get($set, 'tanggal_tes');
                            $tests = data_get($set, 'tests', []);
                        ?>

                        <?php if (isset($set['status']) && $set['status'] === 'pending') : ?>
                            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-6 result-card" data-transaction-id="{{ $txnId }}">
                                <div class="px-6 py-4 bg-green-600 flex items-center justify-between text-white">
                                    <div>
                                        <div class="text-lg font-semibold">Hasil Tes</div>
                                        <div class="text-sm opacity-90">Transaction ID: {{ $txnId }}</div>
                                    </div>
                                    <button type="button" class="no-print inline-flex items-center px-3 py-1 rounded-md text-xs font-medium bg-green-800 hover:bg-green-900 print-card">
                                        <i data-feather="download" class="mr-1"></i> Download
                                    </button>
                                </div>
                                <div class="p-6">
                                    <div class="text-center py-8">
                                        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <i data-feather="clock" class="h-8 w-8 text-yellow-600"></i>
                                        </div>
                                        <h3 class="text-xl font-semibold text-gray-900">Results Pending</h3>
                                        <p class="mt-2 text-gray-600">{{ data_get($set,'message') ?? 'Hasil tes belum tersedia. Silakan cek kembali nanti.' }}</p>
                                    </div>
                                </div>
                            </div>
                        <?php else : ?>
                            <?php foreach ((array) $tests as $test) : ?>
                                <?php $params = (array) data_get($test, 'parameters', []); ?>
                                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-6 result-card" data-transaction-id="{{ $txnId }}">
                                    <div class="px-6 py-4 bg-green-600 flex items-center justify-between text-white">
                                        <div>
                                            <div class="text-lg font-semibold">{{ data_get($test,'name') }}</div>
                                            <div class="text-sm opacity-90">
                                                <?php if (!empty($tanggalTes)) : ?>Tanggal Tes: {{ \Carbon\Carbon::parse($tanggalTes)->format('d M Y') }}<?php endif; ?>
                                            </div>
                                            <div class="text-sm opacity-90">Transaction ID: {{ $txnId }}</div>
                                        </div>
                                        <button type="button" class="no-print inline-flex items-center px-3 py-1 rounded-md text-xs font-medium bg-green-800 hover:bg-green-900 print-card">
                                            <i data-feather="download" class="mr-1"></i> Download
                                        </button>
                                    </div>

                                    <div class="p-6">
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parameter</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Satuan</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-100">
                                                    <?php foreach ($params as $p) : ?>
                                                        <?php
                                                            $val = data_get($p,'value');
                                                            $unit = data_get($p,'unit');
                                                        ?>
                                                        <tr>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ data_get($p,'name') }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $val ?? '-' }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $unit ?? '-' }}</td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
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

        (function() {
            var buttons = document.querySelectorAll('.print-card');
            function clearPrintState() {
                document.body.classList.remove('printing-single');
                var targets = document.querySelectorAll('.result-card.print-target');
                targets.forEach(function(el) { el.classList.remove('print-target'); });
            }

            buttons.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var card = btn.closest('.result-card');
                    if (card) {
                        document.body.classList.add('printing-single');
                        card.classList.add('print-target');
                    }
                    window.print();
                });
            });

            window.addEventListener('afterprint', clearPrintState);
        })();
    </script>

    <style>
        @media print {
            nav, footer, .no-print, .bg-gradient-to-r { display: none !important; }
            body { background: white !important; }
            .shadow-sm, .shadow, .shadow-lg { box-shadow: none !important; }
        }

        @media print {
            body.printing-single .result-card { display: none !important; }
            body.printing-single .result-card.print-target { display: block !important; }
        }
    </style>
</body>
</html>
