@extends('layouts.app')

@section('title', 'Pembayaran Pesanan')

@section('content')
<style>
    .payment-wrapper {
        max-width: 650px;
        margin: 60px auto;
    }
    .payment-card {
        border-radius: 18px;
        border: none;
        box-shadow: 0 6px 24px rgba(0,0,0,0.08);
    }
    .summary-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
    }
    .duitku-logo {
        height: 55px;
    }
    .secure-box {
        background: #f6f9ff;
        border-radius: 12px;
        padding: 14px;
        font-size: 14px;
        display: flex;
        gap: 10px;
        align-items: flex-start;
    }
    .pay-btn {
        padding: 14px;
        font-size: 17px;
        border-radius: 14px;
    }
</style>

<div class="payment-wrapper">

    {{-- HEADER --}}
    <div class="text-center mb-4">
        <i class="bi bi-wallet2 text-danger mb-2" style="font-size: 3rem;"></i>
        <h3 class="fw-bold">Pembayaran Pesanan</h3>
        <p class="text-muted mb-0">
            Kode Pesanan:
            <span class="fw-semibold">{{ $order->code }}</span>
        </p>
    </div>

    {{-- PAYMENT CARD --}}
    <div class="card payment-card mb-4">
        <div class="card-body p-4">

            {{-- TOTAL --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <p class="text-secondary mb-1">Total Pembayaran</p>
                    <h3 class="fw-bold text-success mb-0">
                        Rp {{ number_format($order->grand_total) }}
                    </h3>
                </div>

                <img
                    src="https://www.duitku.com/wp-content/uploads/2022/10/duitku-logo.png"
                    class="duitku-logo"
                    alt="Duitku">
            </div>

            {{-- SECURE INFO --}}
            <div class="secure-box mb-4">
                <i class="bi bi-shield-lock-fill text-primary fs-4"></i>
                <div>
                    Pembayaran Anda diproses melalui
                    <strong>Duitku Secure Payment</strong>.
                    Data Anda terenkripsi & aman.
                </div>
            </div>

            {{-- PAY BUTTON --}}
            <a href="{{ $paymentUrl }}"
               class="btn btn-danger w-100 pay-btn fw-bold">
                <i class="bi bi-box-arrow-up-right me-2"></i>
                Lanjutkan Pembayaran
            </a>

            <p class="text-muted mt-3 small text-center">
                Anda akan diarahkan ke halaman pembayaran Duitku.
            </p>

        </div>
    </div>

    {{-- ORDER SUMMARY --}}
    <div class="card summary-card mb-5">
        <div class="card-body p-4">

            <h5 class="fw-bold mb-3">Ringkasan Pesanan</h5>

            <div class="d-flex justify-content-between mb-2">
                <span>Total Item</span>
                <span class="fw-semibold">
                    {{ $order->items->sum('quantity') }} produk
                </span>
            </div>

            <div class="d-flex justify-content-between">
                <span>Tanggal Order</span>
                <span class="fw-semibold">
                    {{ $order->created_at->format('d M Y H:i') }}
                </span>
            </div>

        </div>
    </div>

</div>

{{-- AUTO REDIRECT (UX NICE) --}}
<script>
    setTimeout(() => {
        window.location.href = "{{ $paymentUrl }}";
    }, 2500);
</script>
@endsection
