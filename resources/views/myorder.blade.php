<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Clinic Lab - My Order</title>
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
                    <a href="{{ route('result') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        <i data-feather="clipboard" class="mr-2"></i> Test Result
                    </a>
                    <a href="{{ route('myorder') }}" class="border-primary-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
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

    <!-- Header -->
    <div class="bg-gradient-to-r from-green-50 to-green-50 py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-extrabold text-gray-900">My Order</h1>
            
        </div>
    </div>

    <!-- Content -->
    <div class="bg-white py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Search Bar -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-feather="search" class="h-5 w-5 text-gray-400"></i>
                </div>
                <input type="text" placeholder="Transaction ID, order ID, patient's name" class="block w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg bg-white placeholder-gray-500 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm" />
                <button class="absolute right-2 top-1/2 -translate-y-1/2 p-2 rounded-md border border-gray-200 bg-white text-gray-500 hover:bg-gray-50">
                    <i data-feather="sliders"></i>
                </button>
            </div>

            <!-- Status Tabs -->
            <div class="mt-4 flex items-center space-x-3">
                <a href="{{ route('myorder', ['tab' => 'current']) }}" 
                   class="px-4 py-1.5 rounded-full border text-sm font-medium {{ ($tab ?? 'current') === 'current' ? 'border-green-200 text-green-700 bg-green-50' : 'border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                    <i data-feather="clock" class="h-4 w-4 mr-1"></i> Current Orders
                </a>
                <a href="{{ route('myorder', ['tab' => 'history']) }}" 
                   class="px-4 py-1.5 rounded-full border text-sm font-medium {{ ($tab ?? 'current') === 'history' ? 'border-green-200 text-green-700 bg-green-50' : 'border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                    <i data-feather="archive" class="h-4 w-4 mr-1"></i> Order History
                </a>
            </div>

            <!-- Orders List -->
            @forelse($bookings as $booking)
            @php
                $testsCollection = $booking->jenisTes ?? ($booking->jenis_tes ?? collect());
                $testsCount = is_object($testsCollection) && method_exists($testsCollection, 'count') ? $testsCollection->count() : (is_array($testsCollection) ? count($testsCollection) : 0);
                $patientName = data_get($booking, 'pasien.nama', '-');
                $amount = (int) data_get($booking, 'pembayaran.jumlah', 0);

                $sesiNumber = data_get($booking, 'sesi') ?? data_get($booking, 'sesi_fallback') ?? session('booking_sesi_' . data_get($booking, 'booking_id'));
                $sesiMap = [
                    1 => 'Sesi 1 (08:00-10:00)',
                    2 => 'Sesi 2 (10:00-12:00)',
                    3 => 'Sesi 3 (13:00-15:00)',
                    4 => 'Sesi 4 (15:00-17:00)',
                ];
                $sesiLabel = $sesiNumber && isset($sesiMap[(int) $sesiNumber]) ? $sesiMap[(int) $sesiNumber] : null;
            @endphp
            <div class="mt-6 bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="p-4 sm:p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i data-feather="flask" class="h-4 w-4 text-green-600 mr-2"></i>
                                <span>Transaction ID</span>
                            </div>
                            <p class="mt-1 font-semibold text-gray-900">{{ $booking->booking_id }}</p>
                        </div>
                        <div class="text-right text-sm text-gray-600">
                            <div>{{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }}</div>
                            <div class="mt-1">{{ $sesiLabel ?? ('Sesi ' . ($sesiNumber ?? '-')) }}</div>
                            <div class="mt-1">{{ $booking->cabang->display_name ?? $booking->cabang->nama_cabang ?? 'Branch' }}</div>
                        </div>
                    </div>

                    <div class="mt-4 rounded-lg border border-gray-200 p-4 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">{{ $patientName }}</p>
                            <p class="mt-1 text-sm text-gray-500">{{ $testsCount }} items · Rp{{ number_format($amount, 0, ',', '.') }}</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold 
                            @if($booking->status_pembayaran == 'pending') text-yellow-700 bg-yellow-50 border border-yellow-200
                            @elseif($booking->status_pembayaran == 'waiting_confirmation') text-yellow-700 bg-yellow-50 border border-yellow-200
                            @elseif($booking->status_pembayaran == 'paid') text-green-700 bg-green-50 border border-green-200
                            @elseif($booking->status_pembayaran == 'confirmed') text-green-700 bg-green-50 border border-green-200
                            @elseif($booking->status_pembayaran == 'completed') text-green-700 bg-green-50 border border-green-200
                            @else text-red-700 bg-red-50 border border-red-200
                            @endif">
                            {{ strtoupper($booking->status_pembayaran) }}
                        </span>
                    </div>

                    <div class="mt-4 flex items-center justify-between">
                        <p class="text-sm text-gray-600">Total {{ $testsCount }} items</p>
                        <p class="text-lg font-bold text-gray-900">Rp{{ number_format($amount, 0, ',', '.') }}</p>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <a href="{{ route('myorder.show', $booking->booking_id) }}" class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                            View Details
                            <i data-feather="arrow-right" class="ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="mt-6 bg-white rounded-xl border border-gray-200 shadow-sm p-8 text-center">
                <i data-feather="shopping-bag" class="h-12 w-12 mx-auto text-gray-400"></i>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No orders found</h3>
                <p class="mt-2 text-gray-500">You haven't made any bookings yet.</p>
                <a href="{{ route('labtest') }}" class="mt-4 inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-white bg-primary-600 hover:bg-primary-700">
                    Browse Tests
                </a>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-50">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 tracking-wider uppercase">Services</h3>
                    <ul class="mt-4 space-y-4">
                        <li><a href="{{ route('labtest') }}" class="text-base text-gray-500 hover:text-primary-600">Lab Test</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 tracking-wider uppercase">Company</h3>
                    <ul class="mt-4 space-y-4">
                        <li><a href="#" class="text-base text-gray-500 hover:text-primary-600">About Us</a></li>
                        <li><a href="#" class="text-base text-gray-500 hover:text-primary-600">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 tracking-wider uppercase">Connect</h3>
                    <div class="mt-4 flex space-x-6">
                        <a href="#" class="text-gray-400 hover:text-primary-600">
                            <i data-feather="instagram"></i>
                        </a>
                    </div>
                </div>
            </div>
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

