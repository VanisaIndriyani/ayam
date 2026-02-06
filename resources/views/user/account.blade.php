@extends('layouts.front')

@section('title', 'Akun Saya')

@push('styles')
<style>
/* PROFILE */
.profile-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #eee;
}

.profile-avatar {
    width: 75px;
    height: 75px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #f1f1f1;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

/* MENU ACCOUNT */
.account-menu .menu-item {
    display: block;
    background: white;
    padding: 14px 18px;
    margin-bottom: 10px;
    border-radius: 10px;
    text-decoration: none;
    color: #222;
    font-weight: 500;
    border: 1px solid #eee;
    transition: 0.2s ease-in-out;
}

.account-menu .menu-item:hover {
    background: #f8f9fa;
    transform: translateX(3px);
}

.logout-btn:hover {
    background: #ffe6e6 !important;
    border-color: #ffcccc !important;
}

/* ORDER CARD */
.order-item {
    border-radius: 12px;
    border-color: #eee !important;
    transition: 0.25s;
}

.order-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 14px rgba(0,0,0,0.08);
}
</style>
@endpush

@section('content')
<div class="container mt-5">

    {{-- PROFILE CARD --}}
    <div class="d-flex align-items-center profile-card p-4 mb-4 rounded shadow-sm bg-white">
        <img src="{{ asset('images/avatar.png') }}" alt="Avatar" class="profile-avatar">
        <div class="ms-3">
            <h5 class="mb-1 fw-bold">{{ auth()->user()->name }}</h5>
            <p class="text-muted mb-0">{{ auth()->user()->email }}</p>
        </div>
    </div>

    {{-- MENU AKUN --}}
    <div class="account-menu mb-5">
        <a href="{{ route('orders.index') }}" class="menu-item">
            <i class="fa-solid fa-receipt me-2"></i> Riwayat Pembelian
        </a>

        <a href="{{ route('user.edit') }}" class="menu-item">
            <i class="fa-solid fa-user-pen me-2"></i> Edit Profil
        </a>

        <a href="{{ route('password.request') }}" class="menu-item">
            <i class="fa-solid fa-key me-2"></i> Ganti Password
        </a>

        <form action="{{ route('logout') }}" method="POST" class="d-block">
            @csrf
            <button class="menu-item logout-btn text-danger w-100 text-start">
                <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
            </button>
        </form>
    </div>

    {{-- === RIWAYAT PESANAN === --}}
    <h4 class="fw-bold mb-3">Riwayat Pesanan Terbaru</h4>

    @forelse ($orders as $order)
        <div class="order-item p-3 rounded border bg-white mb-3 shadow-sm">

            <div class="d-flex justify-content-between">
                <div>
                    <div class="fw-bold">#{{ $order->code }}</div>
                    <div class="text-muted" style="font-size: 13px;">
                        {{ $order->created_at->format('d M Y') }}
                    </div>
                </div>

                <div>
                    <span class="badge 
                        @if($order->status == 'completed') bg-success
                        @elseif($order->status == 'pending') bg-warning text-dark
                        @elseif($order->status == 'shipped') bg-primary
                        @else bg-secondary
                        @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>

            <div class="mt-2">
                Total: <strong>Rp {{ number_format($order->grand_total) }}</strong>
            </div>

            <a href="{{ route('orders.show', $order->id) }}" class="mt-2 btn btn-sm btn-outline-primary">
                Lihat Detail →
            </a>
        </div>

    @empty
        <p class="text-muted">Belum ada pesanan.</p>
    @endforelse

</div>
@endsection
