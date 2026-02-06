@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')

<div class="container py-4" style="max-width: 900px;">

    {{-- ================= HEADER ================= --}}
    <div class="order-card mb-4 d-flex justify-content-between align-items-center">

        <div>
            <h5 class="fw-bold mb-1">Kode Pesanan: {{ $order->code }}</h5>
            <small class="text-muted">
                {{ $order->created_at->format('d M Y H:i') }}
            </small>
        </div>

        <span class="status-label {{ $order->payment_status }}">
            {{ ucfirst($order->payment_status) }}
        </span>
    </div>

    {{-- ================= TRACKING ================= --}}
    <div class="order-card mb-4">
        <h5 class="fw-bold mb-3">Status Pesanan</h5>

        @php
            $steps = [
                'pending'    => 'Pesanan Dibuat',
                'paid'       => 'Pembayaran Berhasil',
                'processing' => 'Diproses',
                'shipped'    => 'Dikirim',
                'completed'  => 'Selesai',
            ];

            $statusKeys = array_keys($steps);
            $current = array_search($order->status, $statusKeys);
        @endphp

        <div class="progress-tracker">
            @foreach ($steps as $key => $label)
                @php $index = array_search($key, $statusKeys); @endphp

                <div class="step 
                    {{ $index < $current ? 'done' : '' }} 
                    {{ $index === $current ? 'active' : '' }}">
                    
                    <div class="circle">
                        @if($index < $current)
                            <i class="bi bi-check-lg"></i>
                        @endif
                    </div>

                    <p class="step-label">{{ $label }}</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ================= PRODUK ================= --}}
    <div class="order-card mb-4">
        <h5 class="fw-bold mb-3">Detail Produk</h5>

        @foreach ($order->items as $item)
            <div class="product-line">
                <div>
                    <b>{{ $item->product_name }}</b>
                    <div class="text-muted small">Qty: {{ $item->quantity }}</div>
                </div>

                <div class="fw-bold">
                    Rp {{ number_format($item->subtotal) }}
                </div>
            </div>
        @endforeach

        <hr>

        <div class="d-flex justify-content-between fw-bold">
            <span>Total Pembayaran</span>
            <span>Rp {{ number_format($order->grand_total) }}</span>
        </div>
    </div>

    {{-- ================= PEMBAYARAN ================= --}}
    @if ($order->payments->first())
        @php $pay = $order->payments->first(); @endphp

        <div class="order-card mb-4">
            <h5 class="fw-bold mb-3">Detail Pembayaran</h5>

            <p class="mb-1">Provider: <b>{{ strtoupper($pay->provider) }}</b></p>
            <p class="mb-1">Status: <b>{{ ucfirst($pay->transaction_status) }}</b></p>
            <p class="mb-1">Jumlah: <b>Rp {{ number_format($pay->gross_amount) }}</b></p>

            @if($pay->payment_url && $order->payment_status !== 'paid')
                <a href="{{ $pay->payment_url }}"
                   class="btn btn-danger mt-3 w-100"
                   target="_blank">
                    Bayar Sekarang
                </a>
            @endif
        </div>
    @endif

    {{-- ================= BUTTON BAYAR ================= --}}
    @if ($order->payment_status === 'pending')
        <a href="{{ route('payment.start', $order->id) }}"
           class="btn btn-danger w-100 py-3 mb-4 fw-bold">
            <i class="bi bi-wallet2 me-2"></i> Lanjutkan Pembayaran
        </a>
    @endif

    {{-- ================= HISTORY ================= --}}
    <div class="order-card mb-4">
        <h5 class="fw-bold mb-3">Riwayat Status</h5>

        @forelse($order->statusHistories as $h)
            <div class="history-line">
                <div class="fw-bold">{{ ucfirst($h->status_to) }}</div>
                <small class="text-muted">
                    {{ $h->description ?? '-' }} —
                    {{ $h->created_at->format('d M Y H:i') }}
                </small>
            </div>
        @empty
            <p class="text-muted">Belum ada riwayat status.</p>
        @endforelse
    </div>

</div>

{{-- ================= STYLE ================= --}}
<style>
.order-card {
    background: #fff;
    border-radius: 14px;
    padding: 20px;
    border: 1px solid #eaeaea;
}

.status-label {
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 14px;
    text-transform: capitalize;
}
.status-label.pending { background:#ffe08a; color:#795500; }
.status-label.paid { background:#4caf50; color:white; }
.status-label.failed { background:#e53935; color:white; }

.progress-tracker {
    display:flex;
    justify-content:space-between;
    position:relative;
}
.progress-tracker::before {
    content:"";
    position:absolute;
    top:18px;
    left:5%;
    width:90%;
    height:4px;
    background:#ddd;
}

.step {
    width:20%;
    text-align:center;
    z-index:2;
}
.circle {
    width:32px;
    height:32px;
    border-radius:50%;
    background:#d5d5d5;
    display:flex;
    justify-content:center;
    align-items:center;
}
.step.done .circle { background:#198754; color:white; }
.step.active .circle { background:#dc3545; color:white; }

.step-label {
    margin-top:8px;
    font-size:13px;
}

.product-line,
.history-line {
    display:flex;
    justify-content:space-between;
    padding:8px 0;
    border-bottom:1px solid #f1f1f1;
}
</style>

@endsection
