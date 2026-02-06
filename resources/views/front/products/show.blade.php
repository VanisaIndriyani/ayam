@extends('layouts.front')

@section('content')

<div class="container py-5">

    <div class="row g-5">

        {{-- FOTO PRODUK --}}
        <div class="col-md-6">

            {{-- FOTO UTAMA --}}
            <div class="border rounded shadow-sm p-2 mb-3">
                <img 
                    src="{{ asset('storage/'.$product->images->first()->path) }}"
                    class="img-fluid rounded"
                    alt="{{ $product->name }}"
                >
            </div>

            {{-- THUMBNAILS --}}
            <div class="d-flex gap-2">
                @foreach ($product->images as $img)
                    <img 
                        src="{{ asset('storage/'.$img->path) }}"
                        class="rounded border"
                        style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                        onclick="document.querySelector('#mainImage').src=this.src"
                    >
                @endforeach
            </div>

        </div>

        {{-- DETAIL PRODUK --}}
        <div class="col-md-6">

            <h1 class="fw-bold mb-3">{{ $product->name }}</h1>

            <h3 class="text-success fw-bold mb-4">
                Rp {{ number_format($product->price) }}
            </h3>

            {{-- Deskripsi --}}
            <p class="text-muted mb-4" style="line-height: 1.6;">
                {{ $product->description }}
            </p>

            {{-- FORM ADD TO CART --}}
            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-4">
                @csrf

                <div class="mb-3">
                    <label for="quantity" class="fw-semibold">Jumlah</label>
                    <input 
                        type="number"
                        name="quantity"
                        id="quantity"
                        value="1"
                        min="1"
                        class="form-control w-25"
                    >
                </div>

                <button class="btn btn-primary px-4 py-2">
                    <i class="bi bi-cart-plus me-1"></i> Tambah ke Keranjang
                </button>
            </form>

        </div>

    </div>

</div>

@endsection
