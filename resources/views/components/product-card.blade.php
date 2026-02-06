<div class="col-md-3 mb-4">
    <div class="card product-card shadow-sm border-0 h-100">

        <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none">
            <img src="{{ $product->image }}"
                 alt="{{ $product->name }}"
                 class="card-img-top"
                 style="height: 220px; object-fit: cover; border-radius: 8px;">
        </a>

        <div class="card-body">
            <a href="{{ route('product.show', $product->slug) }}" 
               class="text-decoration-none text-dark">
                <h6 class="fw-bold text-truncate mb-1">{{ $product->name }}</h6>
            </a>

            <p class="text-success fw-bold mb-1">
                Rp {{ number_format($product->price) }}
            </p>

            <p class="text-muted small mb-3">
                Stok: {{ $product->stock }}
            </p>

            {{-- Tombol PSU (bukan anchor) --}}
            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                @csrf
                <button class="btn btn-primary w-100">
                    Tambah ke Keranjang
                </button>
            </form>
        </div>

    </div>
</div>
