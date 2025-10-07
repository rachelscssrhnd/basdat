<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Clinic Lab - Sign In</title>
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
                        <i data-feather="flask" class="mr-2"></i> Lab Test
                    </a>
                    <a href="{{ route('myorder') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        <i data-feather="shopping-bag" class="mr-2"></i> My Order
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <div class="bg-gradient-to-r from-primary-50 to-secondary-50 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl font-extrabold text-gray-900">Welcome Back</h1>
            <p class="mt-2 text-gray-600">Sign in to manage your orders and lab tests</p>
        </div>
    </div>

    <!-- Auth Card -->
    <div class="bg-white py-12">
        <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <!-- Toggle between Login and Register -->
                <div class="flex mb-6">
                    <button id="login-tab" class="flex-1 py-2 px-4 text-center text-sm font-medium text-white bg-gradient-to-r from-green-500 to-yellow-400 rounded-l-md">
                        Sign In
                    </button>
                    <button id="register-tab" class="flex-1 py-2 px-4 text-center text-sm font-medium text-gray-700 bg-gray-100 rounded-r-md hover:bg-gray-200">
                        Sign Up
                    </button>
                </div>

                <!-- Login Form -->
                <form id="login-form" method="POST" action="{{ route('login.submit') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Username</label>
                        <input type="text" name="username" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="Enter your username" value="{{ old('username') }}">
                        @error('username') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="••••••••">
                        @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="flex items-center text-sm text-gray-600">
                            <input type="checkbox" class="h-4 w-4 text-primary-600 border-gray-300 rounded">
                            <span class="ml-2">Remember me</span>
                        </label>
                        <a href="#" class="text-sm text-primary-600 hover:text-primary-700">Forgot password?</a>
                    </div>
                    @if(session('error'))
                        <div class="text-red-500 text-sm">{{ session('error') }}</div>
                    @endif
                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-green-500 to-yellow-400 hover:from-green-600 hover:to-yellow-500">
                        <i data-feather="log-in" class="mr-2"></i> Sign In
                    </button>
                </form>

                <!-- Register Form -->
                <form id="register-form" method="POST" action="{{ route('register.submit') }}" class="space-y-4 hidden">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Username</label>
                        <input type="text" name="username" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="Choose a username" value="{{ old('username') }}">
                        @error('username') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" name="nama" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="Enter your full name" value="{{ old('nama') }}">
                        @error('nama') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="you@example.com" value="{{ old('email') }}">
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="tel" name="no_hp" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="08123456789" value="{{ old('no_hp') }}">
                        @error('no_hp') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date of Birth</label>
                        <input type="date" name="tgl_lahir" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm" value="{{ old('tgl_lahir') }}">
                        @error('tgl_lahir') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="••••••••">
                        @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input type="password" name="password_confirmation" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="••••••••">
                        @error('password_confirmation') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    @if(session('error'))
                        <div class="text-red-500 text-sm">{{ session('error') }}</div>
                    @endif
                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-green-500 to-yellow-400 hover:from-green-600 hover:to-yellow-500">
                        <i data-feather="user-plus" class="mr-2"></i> Create Account
                    </button>
                </form>

                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">Or continue with</span>
                        </div>
                    </div>
                    <div class="mt-6 grid grid-cols-2 gap-3">
                        <button class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                            <i data-feather="facebook" class="text-blue-600"></i>
                        </button>
                        <button class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                            <i data-feather="mail" class="text-red-500"></i>
                        </button>
                    </div>
                </div>
            </div>
            <p class="mt-6 text-center text-sm text-gray-600">Don't have an account? <a href="#" class="text-primary-600 hover:text-primary-700 font-medium">Create one</a></p>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-50">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 tracking-wider uppercase">Services</h3>
                    <ul class="mt-4 space-y-4">
                        <li><a href="#" class="text-base text-gray-500 hover:text-primary-600">Lab Tests</a></li>
                        <li><a href="#" class="text-base text-gray-500 hover:text-primary-600">Health Packages</a></li>
                        <li><a href="#" class="text-base text-gray-500 hover:text-primary-600">Home Collection</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 tracking-wider uppercase">Company</h3>
                    <ul class="mt-4 space-y-4">
                        <li><a href="#" class="text-base text-gray-500 hover:text-primary-600">About Us</a></li>
                        <li><a href="#" class="text-base text-gray-500 hover:text-primary-600">Careers</a></li>
                        <li><a href="#" class="text-base text-gray-500 hover:text-primary-600">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 tracking-wider uppercase">Legal</h3>
                    <ul class="mt-4 space-y-4">
                        <li><a href="#" class="text-base text-gray-500 hover:text-primary-600">Privacy</a></li>
                        <li><a href="#" class="text-base text-gray-500 hover:text-primary-600">Terms</a></li>
                        <li><a href="#" class="text-base text-gray-500 hover:text-primary-600">Cookie Policy</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 tracking-wider uppercase">Connect</h3>
                    <div class="mt-4 flex space-x-6">
                        <a href="#" class="text-gray-400 hover:text-primary-600">
                            <i data-feather="facebook"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-primary-600">
                            <i data-feather="instagram"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-primary-600">
                            <i data-feather="twitter"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-primary-600">
                            <i data-feather="linkedin"></i>
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

        // Toggle between login and register forms
        document.addEventListener('DOMContentLoaded', function() {
            const loginTab = document.getElementById('login-tab');
            const registerTab = document.getElementById('register-tab');
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');

            loginTab.addEventListener('click', function() {
                loginTab.className = 'flex-1 py-2 px-4 text-center text-sm font-medium text-white bg-gradient-to-r from-green-500 to-yellow-400 rounded-l-md';
                registerTab.className = 'flex-1 py-2 px-4 text-center text-sm font-medium text-gray-700 bg-gray-100 rounded-r-md hover:bg-gray-200';
                loginForm.classList.remove('hidden');
                registerForm.classList.add('hidden');
            });

            registerTab.addEventListener('click', function() {
                registerTab.className = 'flex-1 py-2 px-4 text-center text-sm font-medium text-white bg-gradient-to-r from-green-500 to-yellow-400 rounded-r-md';
                loginTab.className = 'flex-1 py-2 px-4 text-center text-sm font-medium text-gray-700 bg-gray-100 rounded-l-md hover:bg-gray-200';
                registerForm.classList.remove('hidden');
                loginForm.classList.add('hidden');
            });

            // Show register form if mode is register
            @if(isset($mode) && $mode === 'register')
                registerTab.click();
            @endif
        });
    </script>
</body>
</html>
