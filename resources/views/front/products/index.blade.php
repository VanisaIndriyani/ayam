@extends('layouts.app')

@section('title', 'Semua Produk')

@section('content')
<div class="container py-5">

    <h2 class="fw-bold mb-4 text-center">Semua Produk</h2>

    @if ($products->count() == 0)
        <div class="alert alert-info text-center">
            Belum ada produk tersedia.
        </div>
    @endif

    <div class="row g-4">

        @foreach($products as $product)
        <div class="col-lg-3 col-md-4 col-sm-6">

            <div class="product-card shadow-sm">

                {{-- GAMBAR PRODUK FIX --}}
                @php
                    $image = $product->image;

                    if (!$image) {
                        // Cek relasi images jika ada
                        if ($product->images->count() > 0) {
                             $imageUrl = asset('storage/' . $product->images->first()->path);
                        } else {
                             $imageUrl = 'https://via.placeholder.com/400x300?text=No+Image';
                        }
                    } else {
                        // Pastikan tidak double "storage/storage/"
                        // Jika start with images/, berarti di public/images (seed data)
                        if (str_starts_with($image, 'images/')) {
                            $imageUrl = asset($image);
                        } else {
                            $imageUrl = str_contains($image, 'storage/')
                                ? asset($image)
                                : asset('storage/'.$image);
                        }
                    }
                @endphp

                <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="product-img">

                <div class="p-3">

                    <h6 class="fw-bold text-truncate">{{ $product->name }}</h6>

                    <p class="text-success fw-bold mb-1">
                        Rp {{ number_format($product->price) }}
                    </p>

                    <p class="text-muted small mb-3">
                        Stok: {{ $product->stock }}
                    </p>

                    <a href="{{ route('product.show', $product->slug) }}"
                       class="btn btn-danger w-100">
                        Detail Produk
                    </a>

                </div>

            </div>

        </div>
        @endforeach

    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $products->links() }}
    </div>

</div>
@endsection

@push('styles')
<style>
    body {
        background: #f5f6f7 !important;
    }

    .product-card {
        border-radius: 14px;
        background: #fff;
        overflow: hidden;
        transition: .25s ease;
        height: 100%;
    }
    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 18px rgba(0,0,0,0.12);
    }

    .product-img {
        width: 100%;
        height: 210px;
        object-fit: cover;
        background: #eee;
    }
</style>
@endpush
