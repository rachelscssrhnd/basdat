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
                        <i data-feather="activity" class="h-8 w-8 text-primary-600"></i>
                        <span class="ml-2 text-2xl font-bold text-primary-700">E-Clinic Lab</span>
                    </div>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                    <a href="{{ route('home') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        <i data-feather="home" class="mr-2"></i> Home
                    </a>
                    <a href="{{ route('labtest') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        <i data-feather="activity" class="mr-2"></i> Lab Test
                    </a>
                    <a href="{{ route('myorder') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        <i data-feather="shopping-bag" class="mr-2"></i> My Order
                    </a>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    <a href="{{ route('auth') }}" class="text-white px-4 py-2 rounded-md text-sm font-medium flex items-center bg-gradient-to-r from-green-500 to-yellow-400 hover:from-green-600 hover:to-yellow-500">
                        <i data-feather="user" class="mr-2"></i> Sign In
                    </a>
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
                <!-- Order Detail -->
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center"><i data-feather="file-text" class="mr-2 text-green-600"></i> Order Details</h2>
                        <a href="{{ route('labtest') }}" class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium text-white bg-gradient-to-r from-green-500 to-yellow-400 hover:from-green-600 hover:to-yellow-500"><i data-feather="plus" class="mr-2"></i>Add Test</a>
                    </div>
                    <div class="mt-4 divide-y divide-gray-100" id="selected-tests">
                        @if(session('selected_tests'))
                            @foreach(session('selected_tests') as $test)
                                <div class="py-4 flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 bg-primary-100 rounded-md p-3"><i data-feather="heart" class="h-5 w-5 text-primary-600"></i></div>
                                        <div class="ml-4">
                                            <p class="font-medium text-gray-900">{{ $test['nama_tes'] }}</p>
                                            <p class="text-sm text-gray-500">{{ $test['deskripsi'] }}</p>
                                        </div>
                                    </div>
                                    <p class="font-semibold text-gray-900">Rp{{ number_format($test['harga'], 0, ',', '.') }}</p>
                                </div>
                            @endforeach
                        @else
                            <div class="py-4 text-center text-gray-500">
                                <p>No tests selected. <a href="{{ route('labtest') }}" class="text-primary-600 hover:text-primary-700">Browse tests</a></p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Booking Form -->
                <form id="booking-form" method="POST" action="{{ route('booking.store') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Patient Information -->
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center"><i data-feather="user" class="mr-2 text-green-600"></i> Patient Information</h2>
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">First Name</label>
                                <input type="text" name="nama_depan" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm" value="{{ old('nama_depan') }}">
                                @error('nama_depan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Last Name</label>
                                <input type="text" name="nama_belakang" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm" value="{{ old('nama_belakang') }}">
                                @error('nama_belakang') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm" value="{{ old('email') }}">
                                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                                <input type="tel" name="no_hp" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm" value="{{ old('no_hp') }}">
                                @error('no_hp') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

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
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Visit Type</label>
                                <select name="visit_type" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                    <option value="branch" {{ old('visit_type') == 'branch' ? 'selected' : '' }}>Visit Branch</option>
                                    <option value="home" {{ old('visit_type') == 'home' ? 'selected' : '' }}>Home Collection</option>
                                </select>
                                @error('visit_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Selected Tests -->
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center"><i data-feather="flask" class="mr-2 text-green-600"></i> Selected Tests</h2>
                        <div class="mt-4 space-y-3">
                            @foreach($tests as $test)
                                <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                                    <input type="checkbox" name="tes_ids[]" value="{{ $test->tes_id }}" class="h-4 w-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                                    <div class="ml-3 flex-1">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $test->nama_tes }}</p>
                                                <p class="text-sm text-gray-500">{{ $test->deskripsi }}</p>
                                            </div>
                                            <p class="font-semibold text-gray-900">Rp{{ number_format($test->harga, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('tes_ids') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Payment Details -->
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center"><i data-feather="list" class="mr-2 text-green-600"></i> Payment Details</h2>
                        <dl class="mt-4 space-y-2 text-sm text-gray-700" id="payment-summary">
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
                                <input name="payment_method" type="radio" value="card" class="h-4 w-4 text-primary-600">
                                <span class="ml-3 text-sm text-gray-800">Credit/Debit Card</span>
                            </div>
                            <i data-feather="credit-card" class="text-gray-400"></i>
                        </label>
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
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center"><i data-feather="info" class="mr-2 text-green-600"></i> Summary</h2>
                    <p class="mt-2 text-sm text-gray-600">By proceeding, you agree to our terms and conditions. You will receive a confirmation email with your booking details.</p>
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
            const checkboxes = document.querySelectorAll('input[name="tes_ids[]"]');
            const subtotalElement = document.getElementById('subtotal');
            const totalElement = document.getElementById('total');
            
            function updateTotal() {
                let subtotal = 0;
                checkboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        const priceText = checkbox.closest('label').querySelector('.font-semibold').textContent;
                        const price = parseInt(priceText.replace('Rp', '').replace(/\./g, ''));
                        subtotal += price;
                    }
                });
                
                const serviceFee = 5000;
                const total = subtotal + serviceFee;
                
                subtotalElement.textContent = 'Rp' + subtotal.toLocaleString('id-ID');
                totalElement.textContent = 'Rp' + total.toLocaleString('id-ID');
            }
            
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateTotal);
            });
            
            // Initial calculation
            updateTotal();
        });
    </script>
</body>
</html>
