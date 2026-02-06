@extends('layouts.front')

@section('title', 'Akun Saya - BohriFarm')

@section('disable_hero', true)

@push('css')
<style>
    /* OVERRIDE NAVBAR FOR THIS PAGE */
    .navbar {
        background-color: #dc3545 !important; /* Bootstrap Danger Red */
        box-shadow: none !important;
    }
    
    /* BACKGROUND */
    body {
        background-color: #f8f9fa; /* Light grey background for contrast */
    }

    /* PROFILE CARD */
    .profile-card {
        background-color: #ffeaea;
        border: none;
        border-radius: 20px;
    }
    
    .profile-avatar-container {
        width: 100px;
        height: 100px;
        background: #e9ecef;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        color: #adb5bd;
        font-size: 50px;
    }

    /* ORDER HISTORY */
    .order-history-card {
        background-color: #ffeaea;
        border: none;
        border-radius: 20px;
    }

    .order-item-list {
        margin-bottom: 20px;
    }

    .order-item-title {
        font-weight: bold;
        color: #212529;
        font-size: 1.05rem;
    }

    .order-item-meta {
        font-size: 0.9rem;
        color: #6c757d;
    }
</style>
@endpush

@section('content')
<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            
            <!-- PROFILE CARD -->
            <div class="card profile-card shadow-sm mb-4 p-4 text-center">
                
                <!-- Avatar -->
                <div class="mb-3">
                    <div class="profile-avatar-container">
                        <i class="bi bi-person-fill"></i>
                    </div>
                </div>

                <!-- Name & Joined -->
                <h4 class="fw-bold text-danger mb-1">{{ auth()->user()->name }}</h4>
                <p class="text-muted mb-4">Anggota Sejak {{ auth()->user()->created_at->format('Y') }}</p>

                <!-- Details -->
                <div class="text-start mb-4 px-2">
                    <p class="mb-2">
                        <span class="fw-bold text-danger">Email:</span> 
                        <span class="text-dark">{{ auth()->user()->email }}</span>
                    </p>
                    <p class="mb-2">
                        <span class="fw-bold text-danger">No. Telepon:</span> 
                        <span class="text-dark">{{ auth()->user()->phone ?? '-' }}</span>
                    </p>
                    <p class="mb-0">
                        <span class="fw-bold text-danger">Alamat:</span> 
                        <span class="text-dark">{{ auth()->user()->address ?? '-' }}</span>
                    </p>
                </div>

                <!-- Logout Button -->
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100 fw-bold rounded-3 py-2">
                        Keluar Akun
                    </button>
                </form>
            </div>

            <!-- ORDER HISTORY SECTION -->
            <div class="card order-history-card shadow-sm p-4">
                <h5 class="fw-bold text-danger mb-4">
                    <i class="bi bi-clock-history me-2"></i>Riwayat Pesanan
                </h5>

                @if ($orders->count() == 0)
                    <div class="text-center py-3">
                        <p class="text-muted mb-0">Belum ada riwayat pesanan.</p>
                    </div>
                @else
                    @foreach($orders as $order)
                        <div class="order-item-list">
                            <!-- Items Loop -->
                            @foreach($order->items as $item)
                                <div class="mb-2">
                                    <div class="order-item-title">
                                        {{ $item->product_name ?? $item->product->name }} — {{ $item->quantity }} pcs
                                    </div>
                                    <div class="order-item-meta">
                                        Tanggal: {{ $order->created_at->format('d F Y') }} • Total: Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach

                            <!-- If order has multiple items, maybe show total for order? 
                                 The screenshot shows individual items. 
                                 But logically, an order is a group. 
                                 However, to match screenshot "Straw Cake - 2pcs", "Special Pizza - 1pcs",
                                 it seems they want to see ITEMS listed.
                                 
                                 If I list items, I should be careful if an order has 10 items.
                                 But for now, I will list items as per screenshot appearance.
                            -->
                        </div>
                    @endforeach

                    <div class="mt-3">
                        {{ $orders->onEachSide(1)->links('pagination::simple-bootstrap-5') }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
