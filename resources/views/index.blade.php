<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Clinic Lab - Modern Diagnostic Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        },
                        secondary: {
                            50: '#fefce8',
                            100: '#fef9c3',
                            200: '#fef08a',
                            300: '#fde047',
                            400: '#facc15',
                            500: '#eab308',
                            600: '#ca8a04',
                            700: '#a16207',
                            800: '#854d0e',
                            900: '#713f12',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, rgba(34,197,94,0.1) 0%, rgba(234,179,8,0.1) 100%);
        }
        .promo-slide {
            background: linear-gradient(135deg, rgba(34,197,94,0.8) 0%, rgba(234,179,8,0.8) 100%);
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
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
                    <a href="{{ route('home') }}" class="border-primary-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
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
                <div class="-mr-2 flex items-center sm:hidden">
                    <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500" aria-controls="mobile-menu" aria-expanded="false">
                        <i data-feather="menu"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary-50 to-secondary-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:text-center">
                <h2 class="text-base text-primary-600 font-semibold tracking-wide uppercase">Modern Diagnostic Solutions</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Precision Health Testing Made Simple
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-600 lg:mx-auto">
                    Get accurate lab results with our state-of-the-art facilities and expert team.
                </p>
                <div class="mt-8 flex justify-center">
                    <a href="{{ route('labtest') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg text-lg font-medium flex items-center">
                        <i data-feather="activity" class="mr-2"></i>
                        Book a Test Now
                        <i data-feather="arrow-right" class="ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Feature Buttons -->
    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-3 lg:grid-cols-3">
                <div class="feature-card pt-6 transition duration-300 ease-in-out">
                    <div class="flow-root bg-gray-50 rounded-lg px-6 pb-8 h-full">
                        <div class="-mt-6">
                            <div>
                                <span class="inline-flex items-center justify-center p-3 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-md shadow-lg">
                                    <i data-feather="cpu" class="h-6 w-6 text-white"></i>
                                </span>
                            </div>
                            <h3 class="mt-8 text-lg font-medium text-gray-900 tracking-tight">AI Based Test Recommendation</h3>
                            <p class="mt-5 text-base text-gray-500">
                                Our AI analyzes your symptoms and recommends the most appropriate tests for your condition.
                            </p>
                            <a href="{{ route('labtest') }}" class="mt-6 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700">
                                Try Now
                                <i data-feather="arrow-right" class="ml-2"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="feature-card pt-6 transition duration-300 ease-in-out">
                    <div class="flow-root bg-gray-50 rounded-lg px-6 pb-8 h-full">
                        <div class="-mt-6">
                            <div>
                                <span class="inline-flex items-center justify-center p-3 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-md shadow-lg">
                                    <i data-feather="activity" class="h-6 w-6 text-white"></i>
                                </span>
                            </div>
                            <h3 class="mt-8 text-lg font-medium text-gray-900 tracking-tight">Book Lab Tests</h3>
                            <p class="mt-5 text-base text-gray-500">
                                Browse our comprehensive test catalog and schedule appointments with just a few clicks.
                            </p>
                            <a href="{{ route('labtest') }}" class="mt-6 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700">
                                View Tests
                                <i data-feather="arrow-right" class="ml-2"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="feature-card pt-6 transition duration-300 ease-in-out">
                    <div class="flow-root bg-gray-50 rounded-lg px-6 pb-8 h-full">
                        <div class="-mt-6">
                            <div>
                                <span class="inline-flex items-center justify-center p-3 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-md shadow-lg">
                                    <i data-feather="file-text" class="h-6 w-6 text-white"></i>
                                </span>
                            </div>
                            <h3 class="mt-8 text-lg font-medium text-gray-900 tracking-tight">View Test Results</h3>
                            <p class="mt-5 text-base text-gray-500">
                                Access your test reports securely online with detailed explanations from our medical experts.
                            </p>
                            <a href="{{ route('result') }}" class="mt-6 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700">
                                Check Results
                                <i data-feather="arrow-right" class="ml-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Promo Slides -->
    <div class="relative bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-xl shadow-xl">
                <div class="promo-slide rounded-xl p-12 text-white">
                    <div class="md:flex md:items-center md:justify-between">
                        <div class="flex-1 min-w-0">
                            <h2 class="text-2xl font-bold leading-7 sm:text-3xl sm:truncate">
                                Summer Health Checkup Package
                            </h2>
                            <p class="mt-3 text-lg">
                                Get 30% off on our comprehensive health screening package. Limited time offer!
                            </p>
                        </div>
                        <div class="mt-4 flex md:mt-0 md:ml-4">
                            <button class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-primary-700 bg-white hover:bg-gray-50">
                                Learn More
                                <i data-feather="arrow-right" class="ml-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Clinic Locations Map -->
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Our Clinic Locations
                </h2>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
                    Find the nearest Clinic Lab center for your testing needs
                </p>
            </div>
            <div class="mt-12">
                <div class="bg-gray-100 rounded-xl overflow-hidden shadow-lg">
                    <div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-1 space-y-3">
                            <div class="p-3 bg-white rounded-lg shadow cursor-pointer hover:bg-gray-50" onclick="changeMap('cabangA')">
                                <h3 class="text-sm font-semibold text-gray-900">Cabang Klinik A</h3>
                                <p class="text-xs text-gray-600">Jl. Prof. DR. Moestopo No.47, Pacar Kembang, Kec. Tambaksari, Surabaya, Jawa Timur 60132</p>
                            </div>
                            <div class="p-3 bg-white rounded-lg shadow cursor-pointer hover:bg-gray-50" onclick="changeMap('cabangB')">
                                <h3 class="text-sm font-semibold text-gray-900">Cabang Klinik B</h3>
                                <p class="text-xs text-gray-600">Jl. Airlangga No.4 - 6, Airlangga, Kec. Gubeng, Surabaya, Jawa Timur 60115</p>
                            </div>
                            <div class="p-3 bg-white rounded-lg shadow cursor-pointer hover:bg-gray-50" onclick="changeMap('cabangC')">
                                <h3 class="text-sm font-semibold text-gray-900">Cabang Klinik C</h3>
                                <p class="text-xs text-gray-600">Jl. Dr. Ir. H. Soekarno, Mulyorejo, Kec. Mulyorejo, Surabaya, Jawa Timur 60115</p>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <iframe id="mapFrame" class="w-full h-96 border-0"
                                src="https://www.google.com/maps?q=Kampus+A+UNAIR,+Jl.+Prof.+DR.+Moestopo+No.47,+Surabaya&output=embed"
                                allowfullscreen loading="lazy"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-50">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-8">
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
        let gMap, gInfo, gGeocoder;
        function initMap() {
            var mapEl = document.getElementById('map');
            if (!mapEl) return;
            gMap = new google.maps.Map(mapEl, { center: { lat: -6.2, lng: 106.8167 }, zoom: 11 });
            gGeocoder = new google.maps.Geocoder();
            gInfo = new google.maps.InfoWindow();
            const cache = {};

            function geocode(address) {
                if (!address) return Promise.resolve(null);
                if (cache[address]) return Promise.resolve(cache[address]);
                return new Promise((resolve) => {
                    gGeocoder.geocode({ address, componentRestrictions: { country: 'ID' } }, (results, status) => {
                        if (status === 'OK' && results[0]) {
                            cache[address] = {
                                location: results[0].geometry.location,
                                formatted: results[0].formatted_address
                            };
                            resolve(cache[address]);
                        } else { resolve(null); }
                    });
                });
            }

            fetch('/api/branches')
                .then(r => r.json())
                .then(async (branches) => {
                    if (!Array.isArray(branches) || branches.length === 0) return;
                    const bounds = new google.maps.LatLngBounds();
                    for (const b of branches) {
                        const title = b.nama_cabang || 'Cabang';
                        const address = b.alamat || title;
                        const geo = await geocode(address);
                        if (geo) {
                            const marker = new google.maps.Marker({ position: geo.location, map: gMap, title });
                            marker.addListener('click', () => {
                                const shownAddr = geo.formatted || b.alamat || '';
                                gInfo.setContent('<strong>' + title + '</strong><br/>' + shownAddr + '<br/>' + (b.no_telepon || ''));
                                gInfo.open(gMap, marker);
                            });
                            bounds.extend(geo.location);
                        }
                    }
                    if (!bounds.isEmpty()) gMap.fitBounds(bounds);
                })
                .catch(() => {});
        }

        function changeMap(branch) {
            var mapFrame = document.getElementById('mapFrame');
            if (!mapFrame) return;
            if (branch === 'cabangA') {
                mapFrame.src = 'https://www.google.com/maps?q=Kampus+A+UNAIR,+Jl.+Prof.+DR.+Moestopo+No.47,+Surabaya&output=embed';
            } else if (branch === 'cabangB') {
                mapFrame.src = 'https://www.google.com/maps?q=Jl.+Airlangga+No.4-6,+Surabaya&output=embed';
            } else if (branch === 'cabangC') {
                mapFrame.src = 'https://www.google.com/maps?q=Jl.+Dr.+Ir.+H.+Soekarno,+Mulyorejo,+Surabaya&output=embed';
            }
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap" async defer></script>
</body>
</html>
