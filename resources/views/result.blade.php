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
                    <a href="{{ route('myorder') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        <i data-feather="shopping-bag" class="mr-2"></i> My Order
                    </a>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                @if(session()->has('user_id'))
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700">Welcome, {{ session('username') }}</span>
                        <a href="{{ route('myorder') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                            <i data-feather="shopping-bag" class="mr-1"></i> My Orders
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-white px-4 py-2 rounded-md text-sm font-medium bg-red-500 hover:bg-red-600">
                                <i data-feather="log-out" class="mr-1"></i> Logout
                            </button>
                        </form>
                    </div>
                @else
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('register') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                            <i data-feather="user-plus" class="mr-1"></i> Sign Up
                        </a>
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
    <div class="bg-gradient-to-r from-primary-50 to-secondary-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-extrabold text-gray-900 flex items-center"><i data-feather="clipboard" class="mr-3 text-green-600"></i> Test Result Summary</h1>
        @if(isset($result) && $result)
            @php($txnId = data_get($result, 'booking_id'))
            @php($tanggalTes = data_get($result, 'tanggal_tes'))
            @if($txnId)
                <p class="mt-2 text-gray-600">Booking ID: {{ $txnId }}</p>
            @endif
            @if($tanggalTes)
                <p class="mt-1 text-sm text-gray-500">Tanggal Tes: {{ \Carbon\Carbon::parse($tanggalTes)->format('d M Y') }}</p>
            @endif
        @endif
        </div>
    </div>

    <!-- Results -->
    <div class="bg-white py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(isset($result) && $result)
                @if(isset($result['status']) && $result['status'] === 'pending')
                    <!-- Pending Status -->
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i data-feather="clock" class="h-12 w-12 text-yellow-600"></i>
                        </div>
                        <h3 class="text-2xl font-semibold text-gray-900 mb-4">Results Pending</h3>
                        <p class="text-lg text-gray-600 mb-6">{{ $result['message'] ?? 'Your test results are being processed. Please check back later.' }}</p>
                        <div class="inline-flex items-center px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                            <i data-feather="info" class="h-4 w-4 mr-2"></i>
                            Status: Processing
                        </div>
                    </div>
                @elseif(data_get($result, 'tests') && count(data_get($result, 'tests')) > 0)
                    @foreach(data_get($result, 'tests') as $test)
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-6">
                    <div class="px-6 py-4 border-b bg-gray-50 flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">{{ data_get($test, 'name') }}</h2>
                        <a href="{{ route('result.download', data_get($result,'transaction_id')) }}" class="inline-flex items-center px-3 py-1 rounded-md text-xs font-medium text-white bg-primary-600 hover:bg-primary-700">
                            <i data-feather="download" class="mr-1"></i> Download
                        </a>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parameter</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference Range</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Flag</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach(data_get($test, 'parameters', []) as $parameter)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ data_get($parameter, 'name') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ data_get($parameter, 'value') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ data_get($parameter, 'range') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm 
                                            @if(data_get($parameter, 'flag') == 'Normal') text-green-700
                                            @elseif(data_get($parameter, 'flag') == 'Slightly High') text-yellow-700
                                            @else text-red-700
                                            @endif">{{ data_get($parameter, 'flag') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
            @endif
                    <div class="mt-6 flex items-center justify-between">
                        <a href="{{ route('labtest') }}" class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-gray-700 border border-gray-200 hover:bg-gray-50">
                            <i data-feather="arrow-left" class="mr-2"></i> Back to Tests
                        </a>
                        <div class="space-x-3">
                            <button id="download-result" class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-white bg-gradient-to-r from-green-500 to-yellow-400 hover:from-green-600 hover:to-yellow-500">
                                <i data-feather="download" class="mr-2"></i> Download Result
                            </button>
                            <button class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-white bg-primary-600 hover:bg-primary-700">
                                <i data-feather="share-2" class="mr-2"></i> Share
                            </button>
                        </div>
                    </div>
                </div>
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
        // Simple download as PDF via browser print dialog
        (function() {
            var btn = document.getElementById('download-result');
            if (btn) {
                btn.addEventListener('click', function() {
                    window.print();
                });
            }
        })();
    </script>

    <style>
        @media print {
            nav, footer, #download-result, .bg-primary-600, .bg-gradient-to-r { display: none !important; }
            body { background: white !important; }
            .shadow-sm, .shadow, .shadow-lg { box-shadow: none !important; }
        }
    </style>
</body>
</html>
