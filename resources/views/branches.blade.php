<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branch Locations - E-Clinic Lab</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script>
    <style>
        .map-container {
            height: 500px;
            width: 100%;
        }
        .branch-card {
            transition: all 0.3s ease;
        }
        .branch-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center">
                        <i data-feather="activity" class="h-8 w-8 text-primary-600"></i>
                        <span class="ml-2 text-xl font-bold text-gray-900">E-Clinic Lab</span>
                    </a>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    <a href="{{ route('home') }}" class="border-primary-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        <i data-feather="home" class="mr-2"></i> Home
                    </a>
                    <a href="{{ route('labtest') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        <i data-feather="flask" class="mr-2"></i> Lab Test
                    </a>
                    <a href="{{ route('myorder') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        <i data-feather="shopping-bag" class="mr-2"></i> My Order
                    </a>
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

    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-green-500 to-yellow-400 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-extrabold text-white">Our Branch Locations</h1>
                <p class="mt-4 text-xl text-white">Find the nearest E-Clinic Lab branch to you</p>
            </div>
        </div>
    </div>

    <!-- Interactive Map Section -->
    <div class="bg-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Map -->
                <div class="order-2 lg:order-1">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Interactive Map</h2>
                    <div id="map" class="map-container rounded-lg shadow-lg"></div>
                </div>

                <!-- Branch List -->
                <div class="order-1 lg:order-2">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Branch Information</h2>
                    <div class="space-y-6">
                        @foreach($branches as $branch)
                        <div class="branch-card bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                                        <i data-feather="map-pin" class="h-6 w-6 text-primary-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $branch->nama_cabang }}</h3>
                                    <p class="mt-1 text-gray-600">{{ $branch->alamat }}</p>
                                    <div class="mt-3 flex items-center text-sm text-gray-500">
                                        <i data-feather="clock" class="h-4 w-4 mr-1"></i>
                                        <span>{{ $branch->jam_operasional ?? 'Mon-Fri: 8AM-6PM, Sat: 8AM-2PM' }}</span>
                                    </div>
                                    <div class="mt-2 flex items-center text-sm text-gray-500">
                                        <i data-feather="phone" class="h-4 w-4 mr-1"></i>
                                        <span>{{ $branch->no_telepon ?? '+62 21 1234 5678' }}</span>
                                    </div>
                                    <button onclick="showBranchOnMap({{ $loop->index }})" class="mt-3 inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-primary-600 bg-primary-50 hover:bg-primary-100">
                                        <i data-feather="map" class="h-4 w-4 mr-1"></i>
                                        View on Map
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Services Section -->
    <div class="bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-900">Why Choose Our Branches?</h2>
                <p class="mt-4 text-lg text-gray-600">State-of-the-art facilities with expert medical professionals</p>
            </div>
            <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto">
                        <i data-feather="award" class="h-8 w-8 text-primary-600"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">Certified Lab</h3>
                    <p class="mt-2 text-gray-600">All our branches are certified and meet international standards</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto">
                        <i data-feather="users" class="h-8 w-8 text-primary-600"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">Expert Staff</h3>
                    <p class="mt-2 text-gray-600">Professional medical staff with years of experience</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto">
                        <i data-feather="clock" class="h-8 w-8 text-primary-600"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">Quick Results</h3>
                    <p class="mt-2 text-gray-600">Fast and accurate test results delivered on time</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center">
                        <i data-feather="activity" class="h-8 w-8 text-primary-400"></i>
                        <span class="ml-2 text-xl font-bold">E-Clinic Lab</span>
                    </div>
                    <p class="mt-4 text-gray-400">Your trusted partner for accurate and reliable laboratory testing services.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold">Services</h3>
                    <ul class="mt-4 space-y-2 text-gray-400">
                        <li><a href="{{ route('labtest') }}" class="hover:text-white">Lab Tests</a></li>
                        <li><a href="{{ route('booking') }}" class="hover:text-white">Book Appointment</a></li>
                        <li><a href="{{ route('result') }}" class="hover:text-white">View Results</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold">Support</h3>
                    <ul class="mt-4 space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Help Center</a></li>
                        <li><a href="#" class="hover:text-white">Contact Us</a></li>
                        <li><a href="#" class="hover:text-white">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold">Contact</h3>
                    <div class="mt-4 space-y-2 text-gray-400">
                        <p>+62 21 1234 5678</p>
                        <p>info@ecliniclab.com</p>
                        <p>Jakarta, Indonesia</p>
                    </div>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-800 text-center text-gray-400">
                <p>&copy; 2024 E-Clinic Lab. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        AOS.init();
        feather.replace();

        // Sample branch data
        const branches = [
            {
                name: "Jakarta Central",
                address: "Jl. Sudirman No. 123, Jakarta Pusat",
                lat: -6.2088,
                lng: 106.8456,
                phone: "+62 21 1234 5678",
                hours: "Mon-Fri: 8AM-6PM, Sat: 8AM-2PM"
            },
            {
                name: "Jakarta Selatan",
                address: "Jl. Pondok Indah No. 456, Jakarta Selatan",
                lat: -6.2615,
                lng: 106.7806,
                phone: "+62 21 2345 6789",
                hours: "Mon-Fri: 8AM-6PM, Sat: 8AM-2PM"
            },
            {
                name: "Jakarta Utara",
                address: "Jl. Kelapa Gading No. 789, Jakarta Utara",
                lat: -6.1574,
                lng: 106.9106,
                phone: "+62 21 3456 7890",
                hours: "Mon-Fri: 8AM-6PM, Sat: 8AM-2PM"
            }
        ];

        let map;
        let markers = [];

        function initMap() {
            // Initialize map centered on Jakarta
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 11,
                center: { lat: -6.2088, lng: 106.8456 }
            });

            // Add markers for each branch
            branches.forEach((branch, index) => {
                const marker = new google.maps.Marker({
                    position: { lat: branch.lat, lng: branch.lng },
                    map: map,
                    title: branch.name,
                    animation: google.maps.Animation.DROP
                });

                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900">${branch.name}</h3>
                            <p class="text-gray-600 mt-1">${branch.address}</p>
                            <p class="text-gray-500 text-sm mt-2">${branch.phone}</p>
                            <p class="text-gray-500 text-sm">${branch.hours}</p>
                        </div>
                    `
                });

                marker.addListener('click', () => {
                    infoWindow.open(map, marker);
                });

                markers.push(marker);
            });
        }

        function showBranchOnMap(index) {
            if (map && markers[index]) {
                map.setCenter(markers[index].getPosition());
                map.setZoom(15);
                markers[index].setAnimation(google.maps.Animation.BOUNCE);
                setTimeout(() => {
                    markers[index].setAnimation(null);
                }, 2000);
            }
        }

        // Initialize map when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Map will be initialized by Google Maps API callback
        });
    </script>
</body>
</html>
