@extends('layouts.front')

@section('title', 'Checkout')

@section('disable_hero', true)

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endpush

@section('content')

<style>
/* =======================
   GLOBAL LAYOUT CONTROL
======================= */
.checkout-wrapper {
    max-width: 1200px;
    margin: 0 auto;
    padding-top: 100px; /* Reduced space */
    padding-bottom: 60px;
}

/* =======================
   STEPPER
======================= */
.stepper {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 14px;
    margin-bottom: 30px; /* Reduced margin */
}
.stepper-item {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #adb5bd;
    font-weight: 600;
}
.stepper-item.active {
    color: #b30000;
}
.stepper-circle {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #adb5bd;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
}
.stepper-item.active .stepper-circle {
    background: #b30000;
    box-shadow: 0 6px 18px rgba(179,0,0,.3);
}
.stepper-line {
    width: 60px;
    height: 2px;
    background: #dee2e6;
}

/* =======================
   FORM CARD
======================= */
.checkout-form-card {
    min-height: auto;
    display: flex;
    flex-direction: column;
    overflow: visible; /* Ensure content is not clipped */
}

.checkout-form-card .form-label {
    font-weight: 600;
    margin-bottom: 0.4rem;
    font-size: 0.9rem; /* Slightly smaller labels */
}
.form-control:focus,
.form-select:focus {
    border-color: #b30000;
    box-shadow: 0 0 0 .25rem rgba(179,0,0,.12);
}

/* =======================
   SUMMARY CARD
======================= */
.summary-card {
    background: #fff;
    border-radius: 18px;
    border: 1px solid #f1f1f1;
    box-shadow: 0 14px 36px rgba(0,0,0,.08);
}
.sticky-summary {
    position: sticky;
    top: 96px;
}
.total-amount {
    font-size: 1.6rem;
    font-weight: 800;
    color: #b30000;
}

/* =======================
   BUTTON
======================= */
.btn-checkout {
    background: linear-gradient(135deg,#b30000,#d60000);
    border: none;
    border-radius: 14px;
    font-weight: 700;
    letter-spacing: .3px;
    transition: .3s ease;
}
.btn-checkout:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 28px rgba(179,0,0,.35);
}
</style>

<div class="container py-5">
<div class="checkout-wrapper">

    {{-- ================= STEPPER ================= --}}
    <div class="stepper">
        <div class="stepper-item active">
            <div class="stepper-circle"><i class="bi bi-cart-check"></i></div>
            <span>Pesanan</span>
        </div>
        <div class="stepper-line"></div>
        <div class="stepper-item active">
            <div class="stepper-circle">2</div>
            <span>Pengiriman</span>
        </div>
        <div class="stepper-line"></div>
        <div class="stepper-item">
            <div class="stepper-circle">3</div>
            <span>Pembayaran</span>
        </div>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST">
    @csrf

    <div class="row g-4 align-items-start">

        <div class="col-lg-8"> <!-- Removed d-flex to prevent stretch conflicts -->
            <div class="card checkout-form-card border-0 shadow-sm rounded-4 p-3 p-lg-4 w-100">

                <div class="d-flex align-items-center mb-3 pb-2 border-bottom"> <!-- Further reduced -->
                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                         style="width:40px;height:40px;"> <!-- Smaller icon -->
                        <i class="bi bi-geo-alt-fill fs-5"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1">Alamat Pengiriman</h4>
                        <p class="text-muted mb-0">Pastikan alamat lengkap untuk kelancaran pengiriman.</p>
                    </div>
                </div>

                <div class="row g-3 g-lg-4 flex-grow-1"> <!-- Adjusted gutter -->

                    <div class="col-12">
                        <label class="form-label">Nama Penerima</label>
                        <input type="text" name="shipping_name" class="form-control"
                               placeholder="Nama lengkap penerima" required value="{{ Auth::user()->name }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" name="shipping_phone" class="form-control"
                               placeholder="08xxxxxxxxxx" required value="{{ Auth::user()->phone ?? '' }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea name="shipping_address" class="form-control"
                                  rows="3" placeholder="Jalan, RT/RW, Kelurahan, Kecamatan" required>{{ Auth::user()->address ?? '' }}</textarea>
                    </div>

                    <!-- Location Search (Wrapper for Select2) -->
                    <div class="col-12">
                        <label for="destination_search" class="form-label">Cari Kecamatan / Kota</label>
                        <select class="form-select" id="destination_search" name="destination_search" style="width: 100%;">
                            <!-- Select2 will populate this -->
                        </select>
                        <div class="form-text text-muted">Ketik nama kecamatan atau kota Anda (min. 3 huruf)</div>
                        <!-- Hidden input to store standard city ID that controller expects -->
                        <input type="hidden" name="shipping_city" id="shipping_city" value="">
                    </div>

                    <!-- MAP SECTION -->
                    <div class="col-12">
                        <label class="form-label">Titik Lokasi Pengiriman (Wajib untuk Kurir Toko / Lalamove)</label>
                        
                        <!-- Search Map Input -->
                        <div class="input-group mb-2">
                             <input type="text" id="map_search_input" class="form-control" placeholder="Cari nama jalan / gedung / toko (misal: Toko Bangunan Budi)...">
                             <button class="btn btn-danger" type="button" id="btn_search_map">
                                <i class="bi bi-search"></i> Cari Lokasi
                             </button>
                        </div>
                        <div id="map_search_status" class="small text-success fw-bold mb-2" style="display: none;"></div>

                        <div id="map" class="rounded-3 border" style="height: 300px; z-index: 1;"></div>
                        <div class="form-text text-muted"><i class="bi bi-geo-alt"></i> Geser pin merah ke lokasi tepat rumah Anda untuk akurasi ongkir.</div>
                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">
                    </div>

                    <div class="col-12"><hr class="my-2"></div>

                    <div class="col-md-6 mb-2 mb-md-0"> <!-- Added margin bottom for mobile -->
                        <label class="form-label">Kurir</label>
                        <select id="courier" class="form-select">
                            <option value="jne">JNE</option>
k                             <option value="ninja">Ninja Express</option>
                            <option value="jnt">J&T</option>
                            <option value="antar_toko">Antar Toko (Rp 3.000/km)</option>
                            <option value="lalamove">Lalamove (Instan)</option>
                            <option value="ambil_sendiri">Ambil Sendiri</option>

                            @php
                                // Cek apakah ada ayam potong di keranjang
                                $hasFrozenProduct = $cart->items->contains(function($item) { 
                                    return stripos($item->product->name, 'Potong') !== false; 
                                });
                            @endphp

                            @if($hasFrozenProduct)
                                <option value="frozen">Pengiriman Frozen</option>
                            @endif
                        </select>
                    </div>

                    <div class="col-md-6" id="service_container">
                        <label class="form-label">Layanan</label>
                        <select id="service" name="service"
                                class="form-select" disabled required>
                            <option value="">Pilih kota & kurir</option>
                        </select>
                    </div>

                    <!-- REKOMENDASI PENGIRIMAN -->
                    <div class="col-12">
                        <div class="alert alert-info border-0 bg-light shadow-sm d-flex gap-3 align-items-center mb-0">
                            <i class="bi bi-info-circle-fill fs-4 text-primary"></i>
                            <div>
                                <h6 class="fw-bold mb-1 text-primary">Info Pengiriman:</h6>
                                <ul class="mb-0 ps-3 small text-muted">
                                    <li class="mb-2">Untuk pembelian banyak (ayam hidup), disarankan pilih <b>Kurir Lalamove</b> atau <b>Kurir Toko</b> (di antar dengan mobil pickup).</li>
                                    <li>Untuk pembelian sedikit 1-10 ekor (ayam potong), disarankan menggunakan layanan <b>Pengiriman Frozen</b>, karena kurir tersebut memiliki layanan pendingin.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Manual Shipping Cost Input (Hidden by default) -->
                    <div class="col-12 d-none" id="manual_cost_container">
                        <label class="form-label fw-bold small text-uppercase text-secondary">Biaya Ongkir Manual</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text">Rp</span>
                            <input type="number" id="manual_shipping_cost_input" class="form-control" placeholder="0" min="0">
                        </div>
                        <div class="form-text text-danger">* Masukkan nominal ongkir sesuai kesepakatan</div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ================= SUMMARY KANAN ================= --}}
        <div class="col-lg-4 d-flex">
            <div class="summary-card sticky-summary p-4 w-100">

                <h5 class="fw-bold mb-4">Ringkasan Pesanan</h5>

                @foreach ($cart->items as $item)
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <div class="fw-semibold text-truncate" style="max-width:180px;">
                                {{ $item->product->name }}
                            </div>
                            <div class="d-flex align-items-center">
                                <small class="text-muted me-2">x {{ $item->quantity }}</small>
                                <button type="button" class="btn btn-sm btn-link text-danger p-0" 
                                        data-url="{{ route('cart.remove', $item->id) }}"
                                        onclick="removeItem(this)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="fw-bold">
                            Rp {{ number_format($item->subtotal) }}
                        </div>
                    </div>
                @endforeach

                <hr>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal</span>
                    <span class="fw-bold">Rp {{ number_format($total) }}</span>
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Ongkir</span>
                    <span id="ongkir_text" class="fw-bold text-success">-</span>
                </div>

                <div class="d-flex justify-content-between align-items-center border-top pt-3">
                    <span class="fw-bold fs-5">Total</span>
                    <span id="grand_total_text" class="total-amount">
                        Rp {{ number_format($total) }}
                    </span>
                </div>

                <input type="hidden" name="courier" id="courier_hidden">
                <input type="hidden" name="shipping_cost" id="shipping_cost_hidden">

                <button type="submit" class="btn btn-checkout w-100 py-3 mt-4 text-white">
                    Lanjut Pembayaran
                </button>

                <p class="text-center text-muted small mt-3 mb-0">
                    <i class="bi bi-shield-lock"></i> Pembayaran Aman & Terpercaya
                </p>

            </div>
        </div>

    </div>
    </form>

</div>
</div>

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<style>
    .select2-container--bootstrap-5 .select2-selection {
        border-color: #dee2e6;
        padding: 0.375rem 0.75rem;
        min-height: 45px; /* Reduced from 52px */
        display: flex;
        align-items: center;
    }
    
    @media (max-width: 576px) {
        .checkout-wrapper {
            padding-top: 80px;
            padding-bottom: 40px;
        }
        .stepper {
            gap: 5px;
            margin-bottom: 15px;
        }
        .stepper-item span {
            display: none; /* Hide stepper text on small mobile */
        }
        .stepper-circle {
            width: 30px;
            height: 30px;
            font-size: 0.8rem;
        }
        .checkout-form-card {
            padding: 1.25rem !important;
        }
        .form-control, .form-select {
            font-size: 0.9rem;
        }
        .sticky-summary {
            position: relative; /* Disable sticky on mobile so it doesn't cover content */
            top: 0;
            margin-top: 20px;
        }
    }
</style>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    // --- MAP INITIALIZATION ---
    let map, marker;
    // Default Center (Bogor)
    const defaultLat = -6.595038;
    const defaultLng = 106.793311;

    function initMap() {
        if (map) return; // Already initialized

        map = L.map('map').setView([defaultLat, defaultLng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Initial Marker
        marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

        // Event: Drag End
        marker.on('dragend', function(e) {
            const latlng = marker.getLatLng();
            updateCoordinates(latlng.lat, latlng.lng);
        });

        // Event: Map Click
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            updateCoordinates(e.latlng.lat, e.latlng.lng);
        });

        // Try to get user location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                map.setView([lat, lng], 15);
                marker.setLatLng([lat, lng]);
                updateCoordinates(lat, lng);
            });
        }
    }

    function updateCoordinates(lat, lng) {
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        
        // If courier is Antar Toko / Lalamove, update cost immediately
        const courier = document.getElementById('courier').value;
        if (courier === 'antar_toko' || courier === 'lalamove') {
            updateShippingCost();
        }
    }

    // Map Search Handler
    document.getElementById('btn_search_map').addEventListener('click', function() {
        const query = document.getElementById('map_search_input').value;
        const btn = this;
        const statusDiv = document.getElementById('map_search_status');
        
        if (!query || query.length < 3) {
            alert('Masukkan kata kunci pencarian minimal 3 karakter');
            return;
        }

        // UI Loading
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Mencari...';
        btn.disabled = true;
        statusDiv.style.display = 'none';

        fetch('{{ route("checkout.searchMapLocation") }}?q=' + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    // Ambil hasil pertama (paling relevan)
                    const result = data[0];
                    const lat = parseFloat(result.lat);
                    const lon = parseFloat(result.lon);

                    if (map && marker) {
                        map.setView([lat, lon], 17); // Zoom lebih dekat
                        marker.setLatLng([lat, lon]);
                        updateCoordinates(lat, lon);
                        
                        statusDiv.innerHTML = '<i class="bi bi-check-circle"></i> Lokasi ditemukan: ' + result.display_name;
                        statusDiv.style.display = 'block';
                    }
                } else {
                    alert('Lokasi tidak ditemukan. Coba kata kunci lain atau gunakan nama jalan utama.');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Gagal mencari lokasi. Silakan coba lagi.');
            })
            .finally(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
    });
    
    // Allow Enter key in search input
    document.getElementById('map_search_input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault(); // Prevent form submit
            document.getElementById('btn_search_map').click();
        }
    });

    // Initialize Map on Load
    document.addEventListener('DOMContentLoaded', function() {
        initMap();
    });

    function removeItem(btn) {
        const url = btn.dataset.url;
        if (!url) {
            console.error('URL delete tidak ditemukan');
            alert('Terjadi kesalahan: URL hapus tidak valid.');
            return;
        }

        if(!confirm('Hapus item ini?')) return;

        // Visual feedback
        const originalContent = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
        btn.disabled = true;

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                _method: 'DELETE'
            })
        })
        .then(response => {
            if (response.ok) {
                window.location.reload();
            } else {
                alert('Gagal menghapus item. Status: ' + response.status);
                // Reset button
                btn.innerHTML = originalContent;
                btn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan koneksi saat menghapus item.');
            // Reset button
            btn.innerHTML = originalContent;
            btn.disabled = false;
        });
    }

    const courierSelect = document.getElementById('courier');
    const serviceSelect = document.getElementById('service');
    // Using plain JS variable for initial subtotal
    const subtotalValue = {{ $total }};
    
    // Manual Cost Elements
    const manualCostContainer = document.getElementById('manual_cost_container');
    const manualCostInput = document.getElementById('manual_shipping_cost_input');
    
    // Store selected destination ID and Name
    let selectedDestinationId = null;
    let selectedDestinationName = '';

    // Formatter for currency
    const formatRp = (num) => 'Rp ' + new Intl.NumberFormat('id-ID').format(num);

    $(document).ready(function() {
        $('#destination_search').select2({
            theme: 'bootstrap-5',
            placeholder: 'Ketik Kecamatan / Kota tujuan...',
            minimumInputLength: 3,
            ajax: {
                url: '{{ route("checkout.searchLocation") }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    console.log('Searching for:', params.term);
                    return {
                        q: params.term // search term
                    };
                },
                processResults: function (data) {
                    console.log('Search response:', data);
                    // Komerce API returns { meta: {...}, data: [...] }
                    // OR sometimes list directly? Let's handle generic list if my controller returns data directly.
                    // My controller returns $rajaongkir->searchDestination($query) which is the full JSON.
                    // Komerce structure: data: [ {id, label, ...}, ... ]
                    
                    let results = [];
                    if (data && data.data) {
                        results = data.data.map(item => ({
                            id: item.id,
                            text: item.label // label contains full string
                        }));
                    }
                    return {
                        results: results
                    };
                },
                cache: true
            }
        });

        // Event listener for selection
        $('#destination_search').on('select2:select', function (e) {
            var data = e.params.data;
            selectedDestinationId = data.id;
            selectedDestinationName = data.text; // Store the name for geocoding
            
            // Set hidden input value
            document.getElementById('shipping_city').value = data.id; // Using ID for cost calc
            // Also helpful to store label for readable address if needed, but ID is critical for cost
            
            updateShippingCost();
        });
    });

    // --- LOAD SHIPPING COST ---
    async function updateShippingCost() {
        const destination = selectedDestinationId; // Use the Variable
        const courier = courierSelect.value;
        const weight = {{ $cart->items->sum(fn($i) => $i->product->weight * $i->quantity) }}; // Ensure weight is passed
        
        // Visibility Service Container
        const serviceContainer = document.getElementById('service_container');
        if (serviceContainer) serviceContainer.classList.remove('d-none');

        // Reset service selection
        serviceSelect.innerHTML = '<option value="">Pilih kota & kurir</option>';
        serviceSelect.disabled = true;
        
        // Handle Manual Courier (Ambil Sendiri)
        if (courier === 'ambil_sendiri') {
            // Hide manual cost input because it's free (0)
            if (manualCostContainer) manualCostContainer.classList.add('d-none');
            
            serviceSelect.innerHTML = '<option value="ambil_sendiri">Ambil Sendiri (Gratis)</option>';
            serviceSelect.value = 'ambil_sendiri';
            serviceSelect.disabled = true;
            
            updateTotals();
            return;
        } else {
            if (manualCostContainer) manualCostContainer.classList.add('d-none');
        }

        updateTotals(0); // reset cost to 0 while loading

        if (!destination) {
             serviceSelect.innerHTML = '<option value="">Mohon pilih Kota Tujuan dulu</option>';
             // Optional: focus to destination search if they changed courier but no destination
             // $('#destination_search').select2('open'); 
             return;
        }

        if (!courier) return;

        serviceSelect.innerHTML = '<option value="">Sedang mencari ongkir...</option>';
        serviceSelect.disabled = true;
        
        try {
            console.log('Fetching shipping cost for:', { destination, courier, weight });
            
            // Get Coordinates
            const lat = document.getElementById('latitude').value;
            const lng = document.getElementById('longitude').value;

            const response = await fetch('{{ route("checkout.shippingCost") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ 
                    destination: destination, 
                    courier: courier,
                    destination_name: selectedDestinationName, // Send name for Antar Toko
                    latitude: lat,
                    longitude: lng
                })
            });

            const data = await response.json();
            console.log('Shipping cost response:', data);

            // Update Map if coordinates returned (Auto-Center)
            if (data.meta && data.meta.destination_coords) {
                const dLat = parseFloat(data.meta.destination_coords.lat);
                const dLon = parseFloat(data.meta.destination_coords.lon);
                
                if (!isNaN(dLat) && !isNaN(dLon) && map && marker) {
                    const currentCenter = map.getCenter();
                    const dist = Math.sqrt(Math.pow(currentCenter.lat - dLat, 2) + Math.pow(currentCenter.lng - dLon, 2));
                    
                    // Only move if distance is significant (prevent jitter on manual drag)
                    if (dist > 0.0001) {
                        console.log('Moving map to:', dLat, dLon);
                        map.setView([dLat, dLon], 15);
                        marker.setLatLng([dLat, dLon]);
                        // Update inputs silently to match
                        document.getElementById('latitude').value = dLat;
                        document.getElementById('longitude').value = dLon;
                    }
                }
            }

            serviceSelect.innerHTML = '<option value="">-- Pilih Layanan --</option>';
            
            // Komerce layout for cost might be slightly different or standard RajaOngkir
            // Standard RajaOngkir wrapper usually returns: rajaongkir.results[0].costs
            
            let found = false;

            // HANDLE KOMERCE STRUCTURE (data.data.results)
            if (data.data && data.data.results && data.data.results.length > 0) {
                 let results = data.data.results;
                 if (results[0] && results[0].costs && results[0].costs.length > 0) {
                    const costs = results[0].costs;
                    costs.forEach(c => {
                        const costVal = c.cost[0].value;
                        const etd = c.cost[0].etd;
                        const note = c.cost[0].note; // Get note if exists
                        const serviceName = c.service;
                        
                        const opt = document.createElement('option');
                        opt.value = serviceName;
                        opt.dataset.cost = costVal;
                        let text = `${serviceName} - ${formatRp(costVal)} (${etd ? etd + ' hari' : '-'})`;
                        if (note) text += ` [${note}]`;
                        opt.textContent = text;
                        serviceSelect.appendChild(opt);
                    });
                    serviceSelect.disabled = false;
                    found = true;
                 }
            } 
            // HANDLE RAJAONGKIR STANDARD STRUCTURE (rajaongkir.results)
            else if (data.rajaongkir && data.rajaongkir.results) {
                 let results = data.rajaongkir.results;
                 if (results[0] && results[0].costs && results[0].costs.length > 0) {
                     const costs = results[0].costs;
                     costs.forEach(c => {
                        const costVal = c.cost[0].value;
                        const etd = c.cost[0].etd;
                        const note = c.cost[0].note; // Get note
                        const serviceName = c.service;
                        
                        const opt = document.createElement('option');
                        opt.value = serviceName;
                        opt.dataset.cost = costVal;
                        let text = `${serviceName} - ${formatRp(costVal)} (${etd ? etd + ' hari' : '-'})`;
                        if (note) text += ` [${note}]`;
                        opt.textContent = text;
                        serviceSelect.appendChild(opt);
                    });
                     serviceSelect.disabled = false;
                     found = true;
                 }
            } 

            // HANDLE KOMERCE FLAT STRUCTURE (data.data is array of services)
            else if (data.data && Array.isArray(data.data) && data.data.length > 0) {
                 data.data.forEach(c => {
                    const costVal = c.cost; // Direct value
                    const etd = c.etd;
                    const note = c.note; // Get note
                    const serviceName = c.service;
                    const description = c.description || serviceName;
                    
                    const opt = document.createElement('option');
                    opt.value = serviceName;
                    opt.dataset.cost = costVal;
                    let text = `${serviceName} - ${formatRp(costVal)} (${etd ? etd + ' hari' : '-'})`;
                    if (note) text += ` [${note}]`;
                    opt.textContent = text;
                    serviceSelect.appendChild(opt);
                 });
                 serviceSelect.disabled = false;
                 found = true;
            }

            // HANDLE ANTAR TOKO / LALAMOVE STRUCTURE
            else if (data.rajaongkir && data.rajaongkir.results && (data.rajaongkir.results[0].code === 'antar_toko' || data.rajaongkir.results[0].code === 'lalamove')) {
                // Logic Antar Toko yang kita buat sendiri
                let results = data.rajaongkir.results;
                 if (results[0] && results[0].costs && results[0].costs.length > 0) {
                     const costs = results[0].costs;
                     costs.forEach(c => {
                        // Format khusus untuk antar_toko
                        const costData = c.cost[0];
                        const costVal = costData.value;
                        const etdVal = costData.etd;
                        const noteVal = costData.note;

                        const opt = document.createElement('option');
                        opt.value = c.service;
                        opt.dataset.cost = costVal;
                        
                        let optionText = `${c.service} - ${formatRp(costVal)}`;
                        if (etdVal) optionText += ` (Estimasi: ${etdVal} hari)`;
                        if (noteVal) optionText += ` (${noteVal})`;

                        opt.textContent = optionText;
                        serviceSelect.appendChild(opt);
                    });
                     serviceSelect.disabled = false;
                     found = true;
                 }
            }

            if (!found) {
                 // Check if there is an error message
                 let msg = 'Ongkir tidak ditemukan';
                 if (data.meta && data.meta.message) msg = data.meta.message;
                 if (data.rajaongkir && data.rajaongkir.status && data.rajaongkir.status.description != 'OK') {
                    msg = data.rajaongkir.status.description;
                 }
                 serviceSelect.innerHTML = `<option value="">${msg}</option>`;
            } else {
                // Auto select if only 1 option or if courier is antar_toko or lalamove
                if (serviceSelect.options.length === 2 || courier === 'antar_toko' || courier === 'lalamove') {
                     serviceSelect.selectedIndex = 1; // Index 0 is placeholder
                     updateTotals();
                }

                // Hide service dropdown if Antar Toko or Lalamove (User Request)
                if ((courier === 'antar_toko' || courier === 'lalamove') && serviceContainer) {
                    serviceContainer.classList.add('d-none');
                }
            }

        } catch (error) {
            console.error('Error fetching shipping cost:', error);
            serviceSelect.innerHTML = '<option value="">Gagal memuat ongkir</option>';
        }
    }

    courierSelect.addEventListener('change', updateShippingCost);

    // Listener for manual input
    if (manualCostInput) {
        manualCostInput.addEventListener('input', () => updateTotals());
    }

    // --- CALCULATE TOTALS ---
    function updateTotals(explicitCost = null) {
        let cost = 0;
        
        const courier = courierSelect.value;

        if (courier === 'ambil_sendiri') {
            cost = 0; // Ambil sendiri is free
        } else {
            if (explicitCost !== null) {
                cost = explicitCost;
            } else {
            const selected = serviceSelect.options[serviceSelect.selectedIndex];
            if (selected && selected.dataset.cost) {
                cost = parseInt(selected.dataset.cost);
            }
        }
    }

        // Update hidden inputs
        document.getElementById('courier_hidden').value = courierSelect.value;
        document.getElementById('shipping_cost_hidden').value = cost;
        
        // Update UI
        const ongkirText = document.getElementById('ongkir_text');
        const grandTotalText = document.getElementById('grand_total_text');

        if (cost === 0 && courierSelect.value !== 'manual') {
             ongkirText.textContent = '-';
             grandTotalText.textContent = formatRp(subtotalValue);
        } else {
             ongkirText.textContent = formatRp(cost);
             grandTotalText.textContent = formatRp(subtotalValue + cost);
        }
    }

    serviceSelect.addEventListener('change', () => updateTotals());

    // Initialize hidden inputs on load
    document.getElementById('courier_hidden').value = courierSelect.value;

</script>
@endpush
