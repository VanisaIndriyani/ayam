@extends('layouts.app')

@section('title', 'Tracking Pesanan')

@section('content')
<div class="container">

    <h3 class="fw-bold mb-3">
        Tracking Pesanan: {{ $order->code }}
    </h3>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <p class="mb-1"><b>Status Pesanan:</b> <span id="order-status">{{ $order->status }}</span></p>
            <p class="mb-1"><b>No Resi:</b> {{ $order->tracking_number ?? '-' }}</p>
            <p class="mb-0"><b>Terakhir Update:</b> <span id="updated-at">{{ $order->updated_at->diffForHumans() }}</span></p>
        </div>
    </div>

    <div id="map" style="height: 420px; border-radius: 10px;" class="shadow-sm"></div>

</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    // DEFAULT POSITION (fallback)
    let lat = {{ $order->latitude ?? -6.200000 }};
    let lng = {{ $order->longitude ?? 106.816666 }};

    // INIT MAP
    var map = L.map('map').setView([lat, lng], 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);

    // MARKER
    var marker = L.marker([lat, lng]).addTo(map)
        .bindPopup("Lokasi Kurir Saat Ini")
        .openPopup();

    // UPDATE FUNCTION (FETCH EVERY 10s)
    async function fetchLocation() {
        const response = await fetch("{{ route('orders.tracking.data', $order->id) }}");
        const data = await response.json();

        // Skip if data is null
        if (!data.latitude || !data.longitude) return;

        // Update text info
        document.getElementById('order-status').innerText = data.status;
        document.getElementById('updated-at').innerText = data.updated_at;

        // Replace Marker Position with animation
        let newLatLng = new L.LatLng(data.latitude, data.longitude);
        marker.setLatLng(newLatLng);
        map.panTo(newLatLng);
    }

    // AUTO-REFRESH SET INTERVAL
    setInterval(fetchLocation, 10000); // 10 detik
</script>
@endpush
