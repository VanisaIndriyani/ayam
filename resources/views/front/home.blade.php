@extends('layouts.front')

@section('title', 'Bohrifarm — Fresh & Natural Products')

@section('content')

{{-- HERO SECTION --}}
<section class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-md-6">
                <h1 class="fw-bold display-5 mb-3">Selamat Datang di Bohrifarm</h1>

                <p class="text-muted fs-5 mb-4">
                    Produk pertanian segar langsung dari petani. Sehat, aman, dan berkualitas.
                </p>

                <a href="{{ route('products.index') }}" 
                   class="btn btn-primary btn-lg px-4 shadow-sm">
                    Mulai Belanja
                </a>
            </div>

            <div class="col-md-6 d-none d-md-block text-end">
                <img src="{{ asset('images/farm-hero.png') }}"
                     class="img-fluid"
                     style="max-width: 420px;">
            </div>

        </div>
    </div>
</section>



{{-- PRODUK TERBARU --}}
<section class="py-5">
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">Produk Terbaru</h3>

            <a href="{{ route('products.index') }}" 
               class="text-primary fw-semibold text-decoration-none">
                Lihat Semua →
            </a>
        </div>

        <div class="row g-4">
            @forelse($newestProducts as $product)
                @include('components.product-card', ['product' => $product])
            @empty
                <p class="text-muted">Belum ada produk terbaru.</p>
            @endforelse
        </div>

    </div>
</section>



{{-- PRODUK TERLARIS --}}
<section class="py-5 bg-light">
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">Produk Terlaris</h3>

            <a href="{{ route('products.index') }}" 
               class="text-primary fw-semibold text-decoration-none">
                Lihat Semua →
            </a>
        </div>

        <div class="row g-4">
            @forelse($bestSellerProducts as $product)
                @include('components.product-card', ['product' => $product])
            @empty
                <p class="text-muted">Belum ada produk terlaris.</p>
            @endforelse
        </div>

    </div>
</section>

@endsection
