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
                        <i data-feather="activity" class="h-8 w-8 text-green-600"></i>
                        <span class="ml-2 text-2xl font-bold text-green-700">E-Clinic Lab</span>
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
                <button id="tab-payments" class="px-4 py-2 rounded-md text-sm font-medium text-gray-700 border border-gray-200 bg-white">Payment Management</button>
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Test Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Proof</th>
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
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->status_tes }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if(optional($booking->pembayaran)->bukti_path)
                                            <a href="{{ Storage::disk('public')->url($booking->pembayaran->bukti_path) }}" target="_blank" class="text-primary-600 hover:text-primary-700">View</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                        @if($booking->pembayaran && $booking->pembayaran->bukti_pembayaran)
                                            <button onclick="viewPaymentProof({{ $booking->pembayaran->pembayaran_id }})" class="px-3 py-1 rounded-md text-white bg-blue-600 hover:bg-blue-700">View Proof</button>
                                        @endif
                                        <button onclick="verifyPayment({{ $booking->booking_id }})" class="ml-2 px-3 py-1 rounded-md text-white bg-green-600 hover:bg-green-700">Verify Payment</button>
                                        <button onclick='editBooking({{ $booking->booking_id }}, @json($booking->status_pembayaran), @json($booking->status_tes))' class="ml-2 px-3 py-1 rounded-md text-white bg-indigo-600 hover:bg-indigo-700">Edit</button>
                                        <button onclick="deleteBooking({{ $booking->booking_id }})" class="ml-2 px-3 py-1 rounded-md text-white bg-red-600 hover:bg-red-700">Delete</button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No bookings found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Payment Management -->
            <section id="panel-payments" class="space-y-6 hidden">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center"><i data-feather="credit-card" class="mr-2 text-green-600"></i> Payments</h2>
                        <div class="flex space-x-2">
                            <button id="refresh-payments" class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-700 border border-gray-200 hover:bg-gray-50">
                                <i data-feather="refresh-cw" class="mr-2"></i>Refresh
                            </button>
                        </div>
                    </div>
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proof</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="payment-rows" class="bg-white divide-y divide-gray-100"></tbody>
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
        const panelPayments = el('panel-payments');
        const panelTests = el('panel-tests');
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function setActive(tab) {
            const b = el('tab-bookings');
            const p = el('tab-payments');
            const t = el('tab-tests');
            if (tab === 'bookings') {
                b.className = 'px-4 py-2 rounded-md text-sm font-medium text-white bg-gradient-to-r from-green-500 to-yellow-400';
                p.className = 'px-4 py-2 rounded-md text-sm font-medium text-gray-700 border border-gray-200 bg-white';
                t.className = 'px-4 py-2 rounded-md text-sm font-medium text-gray-700 border border-gray-200 bg-white';
                panelBookings.classList.remove('hidden');
                panelPayments.classList.add('hidden');
                panelTests.classList.add('hidden');
            } else if (tab === 'payments') {
                p.className = 'px-4 py-2 rounded-md text-sm font-medium text-white bg-gradient-to-r from-green-500 to-yellow-400';
                b.className = 'px-4 py-2 rounded-md text-sm font-medium text-gray-700 border border-gray-200 bg-white';
                t.className = 'px-4 py-2 rounded-md text-sm font-medium text-gray-700 border border-gray-200 bg-white';
                panelPayments.classList.remove('hidden');
                panelBookings.classList.add('hidden');
                panelTests.classList.add('hidden');
            } else {
                t.className = 'px-4 py-2 rounded-md text-sm font-medium text-white bg-gradient-to-r from-green-500 to-yellow-400';
                b.className = 'px-4 py-2 rounded-md text-sm font-medium text-gray-700 border border-gray-200 bg-white';
                p.className = 'px-4 py-2 rounded-md text-sm font-medium text-gray-700 border border-gray-200 bg-white';
                panelTests.classList.remove('hidden');
                panelBookings.classList.add('hidden');
                panelPayments.classList.add('hidden');
            }
        }

        el('tab-bookings').addEventListener('click', () => setActive('bookings'));
        el('tab-payments').addEventListener('click', () => { setActive('payments'); loadPayments(); });
        el('tab-tests').addEventListener('click', () => setActive('tests'));

        function escapeHtml(s) {
            return String(s ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }

        function loadBookings() {
            fetch('/admin/bookings')
                .then(r => r.json())
                .then(res => {
                    if (!res.success) throw new Error(res.message || 'Failed');
                    const rows = res.data.map(b => {
                        const patient = b.pasien?.nama || 'Unknown';
                        const proof = (b.pembayaran?.bukti_pembayaran || b.pembayaran?.bukti_path) ? 'Yes' : '-';
                        const payStatus = b.status_pembayaran || '-';
                        const testStatus = b.status_tes || '-';
                        const date = b.tanggal_booking || '';
                        const sesi = b.sesi || '';
                        const viewBtn = b.pembayaran?.pembayaran_id ? `<button onclick=\"viewPaymentProof(${b.pembayaran.pembayaran_id})\" class=\"px-3 py-1 rounded-md text-white bg-blue-600 hover:bg-blue-700\">View Proof</button>` : '';
                        return `
                            <tr>
                                <td class=\"px-6 py-4 whitespace-nowrap text-sm text-gray-900\">${b.booking_id}</td>
                                <td class=\"px-6 py-4 whitespace-nowrap text-sm text-gray-900\">${escapeHtml(patient)}</td>
                                <td class=\"px-6 py-4 whitespace-nowrap text-sm text-gray-900\">${escapeHtml(date)}</td>
                                <td class=\"px-6 py-4 whitespace-nowrap text-sm text-gray-900\">${escapeHtml(payStatus)}</td>
                                <td class=\"px-6 py-4 whitespace-nowrap text-sm text-gray-900\">${escapeHtml(testStatus)}</td>
                                <td class=\"px-6 py-4 whitespace-nowrap text-sm text-gray-900\">${escapeHtml(proof)}</td>
                                <td class=\"px-6 py-4 whitespace-nowrap text-right text-sm\">
                                    ${viewBtn}
                                    <button onclick=\"verifyPayment(${b.booking_id})\" class=\"ml-2 px-3 py-1 rounded-md text-white bg-green-600 hover:bg-green-700\">Verify Payment</button>
                                    <button onclick=\"editBooking(${b.booking_id}, '${escapeHtml(payStatus)}', '${escapeHtml(testStatus)}', '${escapeHtml(date)}', '${escapeHtml(sesi)}')\" class=\"ml-2 px-3 py-1 rounded-md text-white bg-blue-600 hover:bg-blue-700\">Edit</button>
                                    <button onclick=\"deleteBooking(${b.booking_id})\" class=\"ml-2 px-3 py-1 rounded-md text-white bg-red-600 hover:bg-red-700\">Delete</button>
                                </td>
                            </tr>
                        `;
                    }).join('');
                    el('booking-rows').innerHTML = rows || `<tr><td colspan=\"7\" class=\"px-6 py-4 text-center text-sm text-gray-500\">No bookings found</td></tr>`;
                })
                .catch(() => {
                    el('booking-rows').innerHTML = `<tr><td colspan=\"7\" class=\"px-6 py-4 text-center text-sm text-gray-500\">Failed to load bookings</td></tr>`;
                });
        }

        function loadPayments() {
            fetch('/admin/payments')
                .then(r => r.json())
                .then(res => {
                    if (!res.success) throw new Error(res.message || 'Failed');
                    const rows = res.data.map(p => {
                        const patient = p.booking?.pasien?.nama || 'Unknown';
                        const proof = p.bukti_pembayaran || p.bukti_path;
                        const proofBtn = proof ? `<button onclick=\"viewPaymentProof(${p.pembayaran_id})\" class=\"px-3 py-1 rounded-md text-white bg-blue-600 hover:bg-blue-700\">Proof</button>` : '-';
                        return `
                            <tr>
                                <td class=\"px-6 py-4 whitespace-nowrap text-sm text-gray-900\">${p.pembayaran_id}</td>
                                <td class=\"px-6 py-4 whitespace-nowrap text-sm text-gray-900\">${p.booking_id}</td>
                                <td class=\"px-6 py-4 whitespace-nowrap text-sm text-gray-900\">${escapeHtml(patient)}</td>
                                <td class=\"px-6 py-4 whitespace-nowrap text-sm text-gray-900\">${Number(p.jumlah || 0).toLocaleString('id-ID')}</td>
                                <td class=\"px-6 py-4 whitespace-nowrap text-sm text-gray-900\">${escapeHtml(p.status)}</td>
                                <td class=\"px-6 py-4 whitespace-nowrap text-sm\">${proofBtn}</td>
                                <td class=\"px-6 py-4 whitespace-nowrap text-right text-sm\">
                                    <button onclick=\"confirmPayment(${p.pembayaran_id})\" class=\"px-3 py-1 rounded-md text-white bg-green-600 hover:bg-green-700\">Confirm</button>
                                    <button onclick=\"rejectPayment(${p.pembayaran_id})\" class=\"ml-2 px-3 py-1 rounded-md text-white bg-yellow-600 hover:bg-yellow-700\">Reject</button>
                                    <button onclick="editPayment(${p.pembayaran_id}, '${escapeHtml(p.status)}', '${escapeHtml(p.metode_bayar)}', '${escapeHtml(p.jumlah)}')" class="ml-2 px-3 py-1 rounded-md text-white bg-blue-600 hover:bg-blue-700">Edit</button>
                                    <button onclick=\"deletePayment(${p.pembayaran_id})\" class=\"ml-2 px-3 py-1 rounded-md text-white bg-red-600 hover:bg-red-700\">Delete</button>
                                </td>
                            </tr>
                        `;
                    }).join('');
                    el('payment-rows').innerHTML = rows || `<tr><td colspan=\"7\" class=\"px-6 py-4 text-center text-sm text-gray-500\">No payments found</td></tr>`;
                })
                .catch(() => {
                    el('payment-rows').innerHTML = `<tr><td colspan=\"7\" class=\"px-6 py-4 text-center text-sm text-gray-500\">Failed to load payments</td></tr>`;
                });
        }

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
                                <button onclick="editTest(${test.tes_id})" class="px-3 py-1 rounded-md text-white bg-indigo-600 hover:bg-indigo-700">Edit</button>
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

        function closeModal() {
            el('modal').classList.add('hidden');
        }

        // Test CRUD functions
        function editTest(id) {
            // Fetch test data first
            fetch(`/admin/tests/${id}`)
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const test = data.data;
                    openModal('Edit Test', `
                        <input id="t-name" class="w-full px-3 py-2 border rounded-md" placeholder="Test Name" value="${test.nama_tes}" required />
                        <textarea id="t-description" class="w-full px-3 py-2 border rounded-md" placeholder="Description">${test.deskripsi || ''}</textarea>
                        <input id="t-price" class="w-full px-3 py-2 border rounded-md" placeholder="Price" type="number" value="${test.harga}" required />
                        <textarea id="t-preparation" class="w-full px-3 py-2 border rounded-md" placeholder="Special Preparation">${test.persiapan_khusus || ''}</textarea>
                        <div class="flex gap-2">
                            <button onclick="updateTest(${id})" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Update Test</button>
                            <button onclick="closeModal()" class="flex-1 px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">Cancel</button>
                        </div>
                    `);
                } else {
                    alert('Failed to load test data');
                }
            })
            .catch(() => alert('Failed to load test data'));
        }

        function updateTest(id) {
            const name = el('t-name').value;
            const description = el('t-description').value;
            const price = el('t-price').value;
            const preparation = el('t-preparation').value;

            if (!name || !price) {
                alert('Name and price are required');
                return;
            }

            fetch(`/admin/tests/${id}`, {
                method: 'PUT',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify({
                    nama_tes: name,
                    deskripsi: description,
                    harga: price,
                    persiapan_khusus: preparation
                })
            })
            .then(r => r.json())
            .then(data => {
                alert(data.message || (data.success ? 'Test updated' : 'Update failed'));
                if (data.success) {
                    closeModal();
                    loadTests();
                }
            })
            .catch(() => alert('Update failed'));
        }

        function deleteTest(id) {
            if (confirm('Are you sure you want to delete this test?')) {
                fetch(`/admin/tests/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrf
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

        function editBooking(id, currentPayStatus = '', currentTestStatus = '', currentDate = '', currentSesi = '') {
            openModal('Edit Booking', `
                <label class="text-sm text-gray-700">Tanggal Booking</label>
                <input id="b-date" type="date" class="w-full px-3 py-2 border rounded-md" />
                <label class="text-sm text-gray-700">Sesi</label>
                <input id="b-sesi" class="w-full px-3 py-2 border rounded-md" placeholder="(optional)" />
                <label class="text-sm text-gray-700">Status Pembayaran</label>
                <select id="b-pay" class="w-full px-3 py-2 border rounded-md">
                    <option value="belum_bayar">belum_bayar</option>
                    <option value="pending">pending</option>
                    <option value="waiting_confirmation">waiting_confirmation</option>
                    <option value="paid">paid</option>
                    <option value="confirmed">confirmed</option>
                    <option value="rejected">rejected</option>
                    <option value="failed">failed</option>
                </select>
                <label class="text-sm text-gray-700">Status Tes</label>
                <select id="b-test" class="w-full px-3 py-2 border rounded-md">
                    <option value="menunggu">menunggu</option>
                    <option value="pending_approval">pending_approval</option>
                    <option value="scheduled">scheduled</option>
                    <option value="approved">approved</option>
                    <option value="in_progress">in_progress</option>
                    <option value="completed">completed</option>
                    <option value="cancelled">cancelled</option>
                    <option value="confirmed">confirmed</option>
                    <option value="rejected">rejected</option>
                </select>
            `, () => {
                fetch(`/admin/bookings/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    },
                    body: JSON.stringify({
                        tanggal_booking: el('b-date').value || null,
                        sesi: el('b-sesi').value || null,
                        status_pembayaran: el('b-pay').value,
                        status_tes: el('b-test').value
                    })
                })
                .then(r => r.json())
                .then(data => {
                    alert(data.message || (data.success ? 'Booking updated' : 'Update failed'));
                    if (data.success) {
                        closeModal();
                        loadBookings();
                    }
                })
                .catch(() => alert('Update failed'));
            });

            if (currentDate) {
                try {
                    el('b-date').value = String(currentDate).slice(0, 10);
                } catch (e) {}
            }
            if (currentSesi) {
                el('b-sesi').value = currentSesi;
            }
            if (currentPayStatus) {
                el('b-pay').value = currentPayStatus;
            }
            if (currentTestStatus) {
                el('b-test').value = currentTestStatus;
            }
        }

        function deleteBooking(id) {
            if (confirm('Are you sure you want to delete this booking?')) {
                fetch(`/admin/bookings/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrf
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadBookings();
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

        function viewPaymentProof(id) {
            fetch(`/admin/payments/${id}/proof`)
            .then(r => r.json())
            .then(data => {
                if (data.success && data.data.proof_url) {
                    // Open payment proof in new window
                    window.open(data.data.proof_url, '_blank');
                } else {
                    alert('Payment proof not found');
                }
            })
            .catch(() => alert('Failed to load payment proof'));
        }

        function verifyPayment(id) {
            fetch(`/admin/bookings/${id}/approve-payment`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf }
            })
            .then(r => r.json())
            .then(data => {
                alert(data.message || (data.success ? 'Payment verified' : 'Verification failed'));
                if (data.success) loadBookings();
            })
            .catch(() => alert('Verification failed'));
        }

        function confirmPayment(id) {
            fetch(`/admin/payments/${id}/confirm`, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf } })
                .then(r => r.json())
                .then(data => {
                    alert(data.message || (data.success ? 'Payment confirmed' : 'Failed'));
                    if (data.success) loadPayments();
                })
                .catch(() => alert('Failed'));
        }

        function rejectPayment(id) {
            openModal('Reject Payment', `
                <textarea id="p-reason" class="w-full px-3 py-2 border rounded-md" placeholder="Reason" required></textarea>
            `, () => {
                fetch(`/admin/payments/${id}/reject`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                    body: JSON.stringify({ reason: el('p-reason').value })
                })
                .then(r => r.json())
                .then(data => {
                    alert(data.message || (data.success ? 'Payment rejected' : 'Failed'));
                    if (data.success) { closeModal(); loadPayments(); }
                })
                .catch(() => alert('Failed'));
            });
        }

        function editPayment(id, status, metode, jumlah) {
            openModal('Edit Payment', `
                <label class="text-sm text-gray-700">Status</label>
                <input id="p-status" class="w-full px-3 py-2 border rounded-md" value="${escapeHtml(status)}" />
                <label class="text-sm text-gray-700">Method</label>
                <input id="p-method" class="w-full px-3 py-2 border rounded-md" value="${escapeHtml(metode)}" />
                <label class="text-sm text-gray-700">Amount</label>
                <input id="p-amount" type="number" class="w-full px-3 py-2 border rounded-md" value="${escapeHtml(jumlah)}" />
                <label class="text-sm text-gray-700">Tanggal Bayar</label>
                <input id="p-date" type="date" class="w-full px-3 py-2 border rounded-md" />
                <label class="text-sm text-gray-700">Alasan Reject</label>
                <textarea id="p-reason" class="w-full px-3 py-2 border rounded-md" placeholder="(optional)"></textarea>
            `, () => {
                fetch(`/admin/payments/${id}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                    body: JSON.stringify({
                        status: el('p-status').value,
                        metode_bayar: el('p-method').value,
                        jumlah: el('p-amount').value
                        ,tanggal_bayar: el('p-date').value || null
                        ,alasan_reject: el('p-reason').value || null
                    })
                })
                .then(r => r.json())
                .then(data => {
                    alert(data.message || (data.success ? 'Payment updated' : 'Failed'));
                    if (data.success) { closeModal(); loadPayments(); }
                })
                .catch(() => alert('Failed'));
            });
        }

        function deletePayment(id) {
            if (!confirm('Delete this payment?')) return;
            fetch(`/admin/payments/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrf } })
                .then(r => r.json())
                .then(data => {
                    alert(data.message || (data.success ? 'Deleted' : 'Failed'));
                    if (data.success) loadPayments();
                })
                .catch(() => alert('Failed'));
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
        el('refresh-bookings').addEventListener('click', () => loadBookings());
        el('refresh-payments').addEventListener('click', () => loadPayments());
        el('refresh-tests').addEventListener('click', () => loadTests());

        // Load tests when tests tab is clicked
        el('tab-tests').addEventListener('click', () => {
            loadTests();
        });

        // Initial load for bookings
        loadBookings();
    </script>
</body>
</html>
