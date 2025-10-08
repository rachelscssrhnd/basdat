<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Clinic Lab - Payment</title>
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
            <h1 class="text-3xl font-extrabold text-gray-900">Payment</h1>
            <p class="mt-2 text-gray-600">Complete your payment to confirm your booking</p>
        </div>
    </div>

    <!-- Payment Content -->
    <div class="bg-white py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Booking Summary -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center mb-4">
                    <i data-feather="file-text" class="mr-2 text-green-600"></i> Booking Summary
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Booking ID</p>
                        <p class="font-semibold text-gray-900">#{{ $booking->booking_id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Date & Session</p>
                        <p class="font-semibold text-gray-900">
                            {{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }} - 
                            Sesi {{ $booking->sesi }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Branch</p>
                        <p class="font-semibold text-gray-900">{{ $booking->cabang->nama_cabang }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Patient</p>
                        <p class="font-semibold text-gray-900">{{ $booking->pasien->nama }}</p>
                    </div>
                </div>
                
                <!-- Selected Tests -->
                <div class="mt-4">
                    <p class="text-sm text-gray-600 mb-2">Selected Tests</p>
                    <div class="space-y-2">
                        @foreach($booking->jenisTes as $test)
                            <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                                <span class="text-sm font-medium">{{ $test->nama_tes }}</span>
                                <span class="text-sm font-semibold">Rp{{ number_format($test->harga, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center mb-4">
                    <i data-feather="credit-card" class="mr-2 text-green-600"></i> 
                    Payment Method: {{ ucfirst($paymentDetails['method']) }}
                </h2>

                @if($paymentDetails['method'] === 'transfer')
                    <!-- Bank Transfer -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                        <div class="text-center">
                            <h3 class="text-lg font-semibold text-blue-900 mb-4">Virtual Account</h3>
                            <div class="bg-white border-2 border-dashed border-blue-300 rounded-lg p-4 mb-4">
                                <p class="text-2xl font-mono font-bold text-blue-900">{{ $paymentDetails['va_number'] }}</p>
                                <p class="text-sm text-blue-600 mt-2">{{ $paymentDetails['bank_name'] }}</p>
                            </div>
                            <p class="text-lg font-semibold text-gray-900">
                                Amount: <span class="text-green-600">Rp{{ number_format($paymentDetails['amount'], 0, ',', '.') }}</span>
                            </p>
                        </div>
                    </div>
                @else
                    <!-- E-Wallet -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                        <div class="text-center">
                            <h3 class="text-lg font-semibold text-green-900 mb-4">{{ $paymentDetails['ewallet_name'] }}</h3>
                            <div class="bg-white border-2 border-dashed border-green-300 rounded-lg p-4 mb-4">
                                <div class="flex justify-center mb-2">
                                    {!! $paymentDetails['qr_code'] !!}
                                </div>
                                <p class="text-sm text-green-600">Scan QR Code</p>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">Or transfer to:</p>
                            <p class="text-lg font-mono font-bold text-green-900">{{ $paymentDetails['ewallet_number'] }}</p>
                            <p class="text-lg font-semibold text-gray-900 mt-2">
                                Amount: <span class="text-green-600">Rp{{ number_format($paymentDetails['amount'], 0, ',', '.') }}</span>
                            </p>
                        </div>
                    </div>
                @endif

                <!-- Instructions -->
                <div class="mt-6">
                    <h4 class="font-semibold text-gray-900 mb-3">Payment Instructions:</h4>
                    <ul class="space-y-2">
                        @foreach($paymentDetails['instructions'] as $instruction)
                            <li class="flex items-start">
                                <span class="text-green-600 mr-2">•</span>
                                <span class="text-sm text-gray-700">{{ $instruction }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Upload Proof -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center mb-4">
                    <i data-feather="upload" class="mr-2 text-green-600"></i> Upload Payment Proof
                </h2>
                
                <form method="POST" action="{{ route('payment.upload', $booking->booking_id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Proof</label>
                            <input type="file" name="payment_proof" required 
                                   accept=".jpg,.jpeg,.png,.pdf"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                            <p class="text-xs text-gray-500 mt-1">Accepted formats: JPG, PNG, PDF (Max 5MB)</p>
                            @error('payment_proof') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <a href="{{ route('myorder') }}" class="text-gray-600 hover:text-gray-800 text-sm">
                                <i data-feather="arrow-left" class="inline mr-1"></i> Back to My Orders
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gradient-to-r from-green-500 to-yellow-400 hover:from-green-600 hover:to-yellow-500">
                                <i data-feather="upload" class="mr-2"></i> Upload Proof
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Status Info -->
            <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i data-feather="info" class="text-yellow-600 mr-3 mt-0.5"></i>
                    <div>
                        <h3 class="text-sm font-medium text-yellow-800">Payment Status</h3>
                        <p class="text-sm text-yellow-700 mt-1">
                            After uploading your payment proof, our admin will verify your payment within 24 hours. 
                            You will receive a notification once your payment is confirmed.
                        </p>
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

        // Show success message if exists
        @if(session('success'))
            alert('{{ session('success') }}');
        @endif

        @if(session('error'))
            alert('{{ session('error') }}');
        @endif
    </script>
</body>
</html>
