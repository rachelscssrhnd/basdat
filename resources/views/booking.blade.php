<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Clinic Lab - Booking</title>
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
            <h1 class="text-3xl font-extrabold text-gray-900">Book Your Test</h1>
            <p class="mt-2 text-gray-600">Review details, set your schedule, and choose payment</p>
        </div>
    </div>

    <!-- Booking Content -->
    <div class="bg-white py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: Details -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Booking Form -->
                <form id="booking-form" method="POST" action="{{ route('booking.store') }}" class="space-y-6">
                    @csrf
                    
                    

                    <!-- Schedule -->
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center"><i data-feather="calendar" class="mr-2 text-green-600"></i> Set Schedule</h2>
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date</label>
                                <input type="date" name="tanggal_booking" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm" value="{{ old('tanggal_booking') }}">
                                @error('tanggal_booking') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Time</label>
                                <input type="time" name="waktu_booking" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm" value="{{ old('waktu_booking', '09:00') }}">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Branch</label>
                                <select name="cabang_id" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                    <option value="">Select Branch</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->cabang_id }}" {{ old('cabang_id') == $branch->cabang_id ? 'selected' : '' }}>{{ $branch->nama_cabang }}</option>
                                    @endforeach
                                </select>
                                @error('cabang_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                        </div>
                    </div>

                <!-- Test Details -->
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center"><i data-feather="flask" class="mr-2 text-green-600"></i> Test Details</h2>
                    <div class="mt-4 space-y-3" id="test-details">
                        @php($selected = isset($selectedTests) ? $selectedTests : collect())
                        @if($selected->isNotEmpty())
                            @foreach($selected as $test)
                                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $test->nama_tes }}</p>
                                        <p class="text-sm text-gray-500">{{ $test->deskripsi }}</p>
                                    </div>
                                    <p class="font-semibold text-gray-900">Rp{{ number_format($test->harga, 0, ',', '.') }}</p>
                                </div>
                                <input type="hidden" name="tes_ids[]" value="{{ $test->tes_id }}" data-price="{{ $test->harga }}">
                            @endforeach
                        @else
                            <p class="text-gray-500">No test selected. Please go back to <a href="{{ route('labtest') }}" class="text-primary-600 hover:text-primary-700">Lab Test</a> to choose a test.</p>
                        @endif
                    </div>
                    @error('tes_ids') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                    <!-- Payment Details -->
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center"><i data-feather="list" class="mr-2 text-green-600"></i> Payment Details</h2>
                        <dl class="mt-4 space-y-2 text-sm text-gray-700" id="payment-summary">
                            <div class="flex justify-between"><dt>Selected Test(s)</dt><dd class="font-medium" id="selected-tests-list">-</dd></div>
                            <div class="flex justify-between"><dt>Subtotal</dt><dd class="font-medium" id="subtotal">Rp0</dd></div>
                            <div class="flex justify-between"><dt>Service Fee</dt><dd class="font-medium">Rp5.000</dd></div>
                            <div class="flex justify-between border-t pt-2 text-base font-semibold text-gray-900"><dt>Total</dt><dd id="total">Rp5.000</dd></div>
                        </dl>
                    </div>
                </form>
            </div>

            <!-- Right: Payment Options -->
            <div class="space-y-6">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center"><i data-feather="credit-card" class="mr-2 text-green-600"></i> Payment Options</h2>
                    <div class="mt-4 space-y-3">
                        <label class="flex items-center justify-between p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                            <div class="flex items-center">
                                <input name="payment_method" type="radio" value="transfer" class="h-4 w-4 text-primary-600">
                                <span class="ml-3 text-sm text-gray-800">Bank Transfer</span>
                            </div>
                            <i data-feather="repeat" class="text-gray-400"></i>
                        </label>
                        <label class="flex items-center justify-between p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                            <div class="flex items-center">
                                <input name="payment_method" type="radio" value="ewallet" class="h-4 w-4 text-primary-600">
                                <span class="ml-3 text-sm text-gray-800">E-Wallet</span>
                            </div>
                            <i data-feather="smartphone" class="text-gray-400"></i>
                        </label>
                    </div>
                    <button type="submit" form="booking-form" class="mt-6 w-full inline-flex items-center justify-center px-4 py-2 rounded-md text-sm font-medium text-white bg-gradient-to-r from-green-500 to-yellow-400 hover:from-green-600 hover:to-yellow-500">
                        <i data-feather="check-circle" class="mr-2"></i> Book Now
                    </button>
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

        // Handle test selection and price calculation
        document.addEventListener('DOMContentLoaded', function() {
            const hiddenTestInputs = document.querySelectorAll('input[name="tes_ids[]"][type="hidden"]');
            const subtotalElement = document.getElementById('subtotal');
            const totalElement = document.getElementById('total');
            const selectedTestsList = document.getElementById('selected-tests-list');
            
            function updateTotal() {
                let subtotal = 0;
                const names = [];
                hiddenTestInputs.forEach(inp => {
                    const price = parseInt(inp.getAttribute('data-price')) || 0;
                    subtotal += price;
                    const row = inp.previousElementSibling;
                    if (row) {
                        const nameEl = row.querySelector('p.font-medium');
                        if (nameEl) names.push(nameEl.textContent);
                    }
                });
                if (selectedTestsList) selectedTestsList.textContent = names.length ? names.join(', ') : '-';
                
                const serviceFee = 5000;
                const total = subtotal + serviceFee;
                
                subtotalElement.textContent = 'Rp' + subtotal.toLocaleString('id-ID');
                totalElement.textContent = 'Rp' + total.toLocaleString('id-ID');
            }
            
            // Initial calculation
            updateTotal();
            @if(session('success'))
                alert(@json(session('success')));
            @endif
        });
    </script>
</body>
</html>
