<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Clinic Lab - Admin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
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
                        <i data-feather="home" class="mr-2"></i> Site
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <div class="bg-gradient-to-r from-primary-50 to-secondary-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-extrabold text-gray-900">Admin Dashboard</h1>
            <p class="mt-2 text-gray-600">Manage bookings and lab tests</p>
            <div class="mt-4 flex space-x-3">
                <button id="tab-bookings" class="px-4 py-2 rounded-md text-sm font-medium text-white bg-gradient-to-r from-green-500 to-yellow-400">Booking Management</button>
                <button id="tab-tests" class="px-4 py-2 rounded-md text-sm font-medium text-gray-700 border border-gray-200 bg-white">Test Management</button>
            </div>
        </div>
    </div>

    <div class="bg-white py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Booking Management -->
            <section id="panel-bookings" class="space-y-6">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center"><i data-feather="clipboard" class="mr-2 text-green-600"></i> Bookings</h2>
                        <div class="flex space-x-2">
                            <button id="refresh-bookings" class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-700 border border-gray-200 hover:bg-gray-50">
                                <i data-feather="refresh-cw" class="mr-2"></i>Refresh
                            </button>
                        </div>
                    </div>
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="booking-rows" class="bg-white divide-y divide-gray-100">
                                @forelse($recentBookings as $booking)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->booking_id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->pasien->nama ?? 'Unknown' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($booking->status_pembayaran == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($booking->status_pembayaran == 'paid') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ strtoupper($booking->status_pembayaran) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                        <button onclick="editBooking({{ $booking->booking_id }})" class="px-3 py-1 rounded-md text-white bg-primary-600 hover:bg-primary-700">Edit</button>
                                        <button onclick="deleteBooking({{ $booking->booking_id }})" class="ml-2 px-3 py-1 rounded-md text-white bg-red-600 hover:bg-red-700">Delete</button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No bookings found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Test Management -->
            <section id="panel-tests" class="space-y-6 hidden">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center"><i data-feather="flask" class="mr-2 text-green-600"></i> Lab Tests</h2>
                        <div class="flex space-x-2">
                            <button id="add-test" class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium text-white bg-gradient-to-r from-green-500 to-yellow-400">
                                <i data-feather="plus" class="mr-2"></i>Add Test
                            </button>
                            <button id="refresh-tests" class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-700 border border-gray-200 hover:bg-gray-50">
                                <i data-feather="refresh-cw" class="mr-2"></i>Refresh
                            </button>
                        </div>
                    </div>
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="test-rows" class="bg-white divide-y divide-gray-100">
                                <!-- Tests will be loaded dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- CRUD Modal -->
    <div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-30 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6">
            <h3 id="modal-title" class="text-lg font-semibold text-gray-900">Create</h3>
            <form id="modal-form" class="mt-4 space-y-3"></form>
            <div class="mt-6 flex justify-end space-x-3">
                <button id="modal-cancel" class="px-4 py-2 rounded-md border border-gray-200 text-gray-700">Cancel</button>
                <button id="modal-submit" class="px-4 py-2 rounded-md text-white bg-gradient-to-r from-green-500 to-yellow-400">Save</button>
            </div>
        </div>
    </div>

    <script>
        feather.replace();

        const el = (id) => document.getElementById(id);
        const panelBookings = el('panel-bookings');
        const panelTests = el('panel-tests');

        function setActive(tab) {
            const b = el('tab-bookings');
            const t = el('tab-tests');
            if (tab === 'bookings') {
                b.className = 'px-4 py-2 rounded-md text-sm font-medium text-white bg-gradient-to-r from-green-500 to-yellow-400';
                t.className = 'px-4 py-2 rounded-md text-sm font-medium text-gray-700 border border-gray-200 bg-white';
                panelBookings.classList.remove('hidden');
                panelTests.classList.add('hidden');
            } else {
                t.className = 'px-4 py-2 rounded-md text-sm font-medium text-white bg-gradient-to-r from-green-500 to-yellow-400';
                b.className = 'px-4 py-2 rounded-md text-sm font-medium text-gray-700 border border-gray-200 bg-white';
                panelTests.classList.remove('hidden');
                panelBookings.classList.add('hidden');
            }
        }

        el('tab-bookings').addEventListener('click', () => setActive('bookings'));
        el('tab-tests').addEventListener('click', () => setActive('tests'));

        // Load tests dynamically
        function loadTests() {
            fetch('/admin/tests')
                .then(response => response.json())
                .then(tests => {
                    const testRows = el('test-rows');
                    testRows.innerHTML = tests.map(test => `
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${test.tes_id}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${test.nama_tes}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${test.deskripsi || 'No description'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp${parseInt(test.harga).toLocaleString('id-ID')}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <button onclick="editTest(${test.tes_id})" class="px-3 py-1 rounded-md text-white bg-primary-600 hover:bg-primary-700">Edit</button>
                                <button onclick="deleteTest(${test.tes_id})" class="ml-2 px-3 py-1 rounded-md text-white bg-red-600 hover:bg-red-700">Delete</button>
                            </td>
                        </tr>
                    `).join('');
                })
                .catch(error => console.error('Error loading tests:', error));
        }

        function openModal(title, formHtml, onSubmit) {
            el('modal-title').textContent = title;
            el('modal-form').innerHTML = formHtml;
            el('modal').classList.remove('hidden');
            el('modal-cancel').onclick = () => el('modal').classList.add('hidden');
            el('modal-submit').onclick = (e) => { e.preventDefault(); onSubmit(); };
        }

        // Test CRUD functions
        function editTest(id) {
            // Implementation for editing test
            console.log('Edit test:', id);
        }

        function deleteTest(id) {
            if (confirm('Are you sure you want to delete this test?')) {
                fetch(`/admin/tests/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadTests();
                        alert('Test deleted successfully');
                    } else {
                        alert('Failed to delete test');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to delete test');
                });
            }
        }

        function editBooking(id) {
            // Implementation for editing booking
            console.log('Edit booking:', id);
        }

        function deleteBooking(id) {
            if (confirm('Are you sure you want to delete this booking?')) {
                fetch(`/admin/bookings/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed to delete booking');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to delete booking');
                });
            }
        }

        // Add test functionality
        el('add-test').addEventListener('click', () => {
            openModal('Add Test', `
                <input id="t-name" class="w-full px-3 py-2 border rounded-md" placeholder="Test Name" required />
                <textarea id="t-description" class="w-full px-3 py-2 border rounded-md" placeholder="Description"></textarea>
                <input id="t-price" type="number" class="w-full px-3 py-2 border rounded-md" placeholder="Price" required />
                <textarea id="t-preparation" class="w-full px-3 py-2 border rounded-md" placeholder="Special Preparation"></textarea>
            `, () => {
                const formData = {
                    nama_tes: el('t-name').value,
                    deskripsi: el('t-description').value,
                    harga: el('t-price').value,
                    persiapan_khusus: el('t-preparation').value
                };

                fetch('/admin/tests', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        el('modal').classList.add('hidden');
                        loadTests();
                        alert('Test created successfully');
                    } else {
                        alert('Failed to create test');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to create test');
                });
            });
        });

        // Refresh buttons
        el('refresh-bookings').addEventListener('click', () => location.reload());
        el('refresh-tests').addEventListener('click', () => loadTests());

        // Load tests when tests tab is clicked
        el('tab-tests').addEventListener('click', () => {
            loadTests();
        });
    </script>
</body>
</html>
