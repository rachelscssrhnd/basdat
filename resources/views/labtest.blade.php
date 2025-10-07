<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Clinic Lab - Book Lab Tests</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <style>
        .test-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .promo-banner {
            background: linear-gradient(135deg, rgba(34,197,94,0.8) 0%, rgba(234,179,8,0.8) 100%);
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
                    <a href="{{ route('home') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        <i data-feather="home" class="mr-2"></i> Home
                    </a>
                    <a href="{{ route('labtest') }}" class="border-primary-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        <i data-feather="flask" class="mr-2 text-green-600"></i> Lab Test
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

    <!-- Page Header -->
    <div class="bg-gradient-to-r from-primary-50 to-secondary-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    <span class="block">Book Your Lab Tests</span>
                </h1>
                <p class="mt-3 max-w-md mx-auto text-base text-gray-600 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                    Comprehensive testing options with fast, accurate results
                </p>
            </div>
        </div>
    </div>

    <!-- Search and Referral Section -->
    <div class="bg-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <!-- Search and Filter Bar -->
                <div class="md:col-span-3">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-feather="search" class="h-5 w-5 text-gray-400"></i>
                            </div>
                            <input type="text" id="search-input" class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="Search lab tests...">
                        </div>
                        <div class="flex gap-2">
                            <select id="price-filter" class="px-3 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                <option value="">All Prices</option>
                                <option value="0-50000">Under Rp50,000</option>
                                <option value="50000-100000">Rp50,000 - Rp100,000</option>
                                <option value="100000-150000">Rp100,000 - Rp150,000</option>
                                <option value="150000-999999">Above Rp150,000</option>
                            </select>
                            <button id="clear-filters" class="px-4 py-3 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-primary-500">
                                Clear
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Test Catalog -->
    <div class="bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900">Available Lab Tests</h2>
                <div id="test-count" class="mt-2 text-sm text-gray-600">{{ $tests->count() }} tests available</div>
                <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3" id="tests-container">
                    @forelse($tests as $test)
                    <div class="test-card bg-white rounded-lg overflow-hidden shadow transition duration-300 ease-in-out">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-primary-100 rounded-md p-3">
                                    <i data-feather="heart" class="h-6 w-6 text-primary-600"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $test->nama_tes }}</h3>
                                    <p class="mt-1 text-sm text-gray-500">{{ $test->deskripsi ?? 'Deskripsi tidak tersedia.' }}</p>
                                </div>
                            </div>
                            <div class="mt-6 flex items-baseline">
                                <span class="text-2xl font-extrabold text-gray-900">Rp{{ number_format($test->harga, 0, ',', '.') }}</span>
                                @if($test->harga < 100000)
                                    <span class="ml-2 text-sm font-medium text-gray-500 line-through">Rp{{ number_format($test->harga * 1.3, 0, ',', '.') }}</span>
                                    <span class="ml-auto inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        25% off
                                    </span>
                                @endif
                            </div>
                            <a href="{{ route('booking', ['test_id' => $test->tes_id]) }}" class="mt-6 w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-green-500 to-yellow-400 hover:from-green-600 hover:to-yellow-500">
                                Book Now
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center py-12">
                        <i data-feather="flask" class="h-12 w-12 mx-auto text-gray-400"></i>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No tests available</h3>
                        <p class="mt-2 text-gray-500">Please check back later for available lab tests.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            

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

        // Search and filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const priceFilter = document.getElementById('price-filter');
            const clearFilters = document.getElementById('clear-filters');
            const testsContainer = document.getElementById('tests-container');
            const testCount = document.getElementById('test-count');

            let searchTimeout;

            // Search functionality
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    performSearch();
                }, 300);
            });

            // Price filter functionality
            priceFilter.addEventListener('change', function() {
                performSearch();
            });

            // Clear filters
            clearFilters.addEventListener('click', function() {
                searchInput.value = '';
                priceFilter.value = '';
                loadAllTests();
            });

            function performSearch() {
                const query = searchInput.value;
                const priceRange = priceFilter.value;
                
                let url = '/labtest/search?q=' + encodeURIComponent(query);
                
                if (priceRange) {
                    const [minPrice, maxPrice] = priceRange.split('-');
                    url = '/labtest/filter?min_price=' + minPrice + '&max_price=' + maxPrice;
                    if (query) {
                        // If both search and filter, we need to filter the search results
                        fetch('/labtest/search?q=' + encodeURIComponent(query))
                            .then(response => response.json())
                            .then(tests => {
                                const filteredTests = tests.filter(test => {
                                    const price = parseInt(test.harga);
                                    return price >= parseInt(minPrice) && price <= parseInt(maxPrice);
                                });
                                displayTests(filteredTests);
                            });
                        return;
                    }
                }
                
                fetch(url)
                    .then(response => response.json())
                    .then(tests => {
                        displayTests(tests);
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                    });
            }

            function displayTests(tests) {
                if (tests.length === 0) {
                    testsContainer.innerHTML = `
                        <div class="col-span-full text-center py-12">
                            <i data-feather="search" class="h-12 w-12 mx-auto text-gray-400"></i>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">No tests found</h3>
                            <p class="mt-2 text-gray-500">Try adjusting your search criteria</p>
                        </div>
                    `;
                } else {
                    testsContainer.innerHTML = tests.map(test => `
                        <div class="test-card bg-white rounded-lg overflow-hidden shadow transition duration-300 ease-in-out">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-primary-100 rounded-md p-3">
                                        <i data-feather="heart" class="h-6 w-6 text-primary-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-medium text-gray-900">${test.nama_tes}</h3>
                                        <p class="mt-1 text-sm text-gray-500">${test.deskripsi || 'No description available'}</p>
                                    </div>
                                </div>
                                <div class="mt-6 flex items-baseline">
                                    <span class="text-2xl font-extrabold text-gray-900">Rp${parseInt(test.harga).toLocaleString('id-ID')}</span>
                                    ${test.harga < 100000 ? `
                                        <span class="ml-2 text-sm font-medium text-gray-500 line-through">Rp${parseInt(test.harga * 1.3).toLocaleString('id-ID')}</span>
                                        <span class="ml-auto inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            25% off
                                        </span>
                                    ` : ''}
                                </div>
                                <a href="/booking?test_id=${test.tes_id}" class="mt-6 w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-green-500 to-yellow-400 hover:from-green-600 hover:to-yellow-500">
                                    Book Now
                                </a>
                            </div>
                        </div>
                    `).join('');
                }
                
                testCount.textContent = `${tests.length} tests found`;
                feather.replace();
            }

            function loadAllTests() {
                fetch('/labtest')
                    .then(response => response.text())
                    .then(html => {
                        // This would require a more complex implementation
                        // For now, just reload the page
                        window.location.reload();
                    });
            }
        });
    </script>
</body>
</html>
