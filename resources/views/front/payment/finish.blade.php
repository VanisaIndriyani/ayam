@extends('layouts.front')

@section('title', 'Status Pembayaran - Bohri Farm')

@section('content')
<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                {{-- Header Decor --}}
                <div class="py-4 text-center @if($order->payment_status == 'paid') bg-success @elseif($order->payment_status == 'pending') bg-warning @else bg-danger @endif">
                    @if($order->payment_status == 'paid')
                        <i class="bi bi-check-circle-fill text-white" style="font-size: 5rem;"></i>
                    @elseif($order->payment_status == 'pending')
                        <i class="bi bi-clock-history text-white" style="font-size: 5rem;"></i>
                    @else
                        <i class="bi bi-x-circle-fill text-white" style="font-size: 5rem;"></i>
                    @endif
                </div>

                <div class="card-body p-4 p-md-5 text-center">
                    <h2 class="fw-bold mb-2">
                        @if($order->payment_status == 'paid')
                            Pembayaran Berhasil!
                        @elseif($order->payment_status == 'pending')
                            Menunggu Pembayaran
                        @else
                            Pembayaran Gagal
                        @endif
                    </h2>
                    <p class="text-muted mb-4 fs-5">
                        @if($order->payment_status == 'paid')
                            Terima kasih! Pesanan Anda sedang kami proses.
                        @elseif($order->payment_status == 'pending')
                            Silakan selesaikan pembayaran Anda agar pesanan dapat diproses.
                        @else
                            Maaf, transaksi Anda tidak dapat dilanjutkan. Silakan coba lagi.
                        @endif
                    </p>

                    <div class="bg-light rounded-4 p-4 text-start mb-4">
                        <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                            <span class="text-muted">Kode Pesanan</span>
                            <span class="fw-bold text-dark">#{{ $order->code }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                            <span class="text-muted">Waktu Transaksi</span>
                            <span class="fw-medium text-dark">{{ $order->paid_at ? $order->paid_at->format('d M Y, H:i') : $order->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                            <span class="text-muted">Metode Pembayaran</span>
                            <span class="fw-medium text-dark">
                                @if($order->payments->first())
                                    {{ $order->payments->first()->payment_type ?: 'Duitku' }}
                                @else
                                    Duitku Payment
                                @endif
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <span class="text-muted fw-bold">Total Pembayaran</span>
                            <span class="fw-bold text-success fs-5">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    @if($order->payment_status == 'pending' && $order->payments->first() && $order->payments->first()->payment_url)
                        <div class="alert alert-info border-0 rounded-4 mb-4">
                            <i class="bi bi-info-circle me-2"></i> Klik tombol di bawah untuk melanjutkan pembayaran di halaman Duitku.
                        </div>
                        <a href="{{ $order->payments->first()->payment_url }}" class="btn btn-warning w-100 py-3 fw-bold rounded-pill shadow-sm mb-3">
                            <i class="bi bi-credit-card me-2"></i> Lanjutkan Pembayaran
                        </a>
                    @endif

                    <div class="d-grid gap-3">
                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary py-3 fw-bold rounded-pill shadow-sm">
                            <i class="bi bi-receipt me-2"></i> Lihat Detail Pesanan
                        </a>
                        <a href="{{ url('/') }}" class="btn btn-outline-secondary py-3 fw-bold rounded-pill">
                            <i class="bi bi-house-door me-2"></i> Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4 text-muted">
                <small>&copy; {{ date('Y') }} Bohri Farm. Seluruh Hak Cipta Dilindungi.</small>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-light {
        background-color: #f8f9fa !important;
    }
    .rounded-4 {
        border-radius: 1rem !important;
    }
    .btn-primary {
        background: linear-gradient(45deg, #198754, #20c997);
        border: none;
    }
    .btn-primary:hover {
        background: linear-gradient(45deg, #157347, #1ba87e);
        transform: translateY(-2px);
    }
</style>
@endsection
