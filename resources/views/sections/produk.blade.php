{{-- ==== PROTECTOR ==== --}}
@if(!isset($products) || $products === null)
    @php return; @endphp
@endif

<section class="noscroll" id="pageProduk" data-login-route="{{ route('login') }}">

    {{-- ================= HEADER ================= --}}
    <section class="text-center mt-5 pt-5">
        <h2 class="fw-bold">Produk Kami</h2>
        <p class="text-muted">
            Temukan berbagai hasil peternakan segar dan berkualitas dari Bohri Farm.
        </p>
    </section>

    <div class="slider-wrap">
        <div class="carousel">
            <div class="track" id="track">

                {{-- ================= PRODUCT CARD ================= --}}
                @forelse($products as $index => $product)
                    @php
                        $stock = $product->stock ?? 0;

                        if ($stock <= 0) {
                            $stockLabel = 'Habis';
                            $stockClass = 'stock-out';
                        } elseif ($stock <= 5) {
                            $stockLabel = 'Stok Menipis';
                            $stockClass = 'stock-low';
                        } else {
                            $stockLabel = 'Stok: ' . $stock;
                            $stockClass = 'stock-ok';
                        }
                    @endphp

                    <div class="card {{ $index === 0 ? 'active' : '' }}"
                        data-id="{{ $product->id }}"
                        data-name="{{ $product->name }}"
                        data-price="{{ $product->price }}"
                        data-price-display="Rp {{ number_format($product->price) }}"
                        data-description="{{ Str::limit(strip_tags($product->description), 140, '...') }}">

                        {{-- STOCK BADGE --}}
                        <div class="stock-badge {{ $stockClass }}">
                            {{ $stockLabel }}
                        </div>

                        {{-- IMAGE --}}
                        @php
                             $bgImage = asset('images/default-product.jpg');
                             if (!empty($product->image)) {
                                 if (str_starts_with($product->image, 'images/')) {
                                     $bgImage = asset($product->image);
                                 } else {
                                     $bgImage = asset('storage/' . $product->image);
                                 }
                             } elseif ($product->images->count() > 0) {
                                 $bgImage = asset('storage/' . $product->images->first()->path);
                             }
                        @endphp
                        <div class="img"
                            style="
                                background-image: url('{{ $bgImage }}');
                                background-size: cover;
                                background-position: center;
                            ">
                        </div>

                        {{-- PRICE --}}
                        <div class="price-badge">
                            Rp {{ number_format($product->price) }}
                        </div>

                        {{-- TITLE --}}
                        <h3 class="title">{{ $product->name }}</h3>
                    </div>

                @empty
                    <p class="text-center text-muted w-100 py-5">
                        Produk belum tersedia.
                    </p>
                @endforelse

            </div>
        </div>

        {{-- ================= CONTROLS ================= --}}
        @if($products->count() > 1)
        <div class="controls">
            <div class="dots" id="dots"></div>
            <button class="btn-circle" id="prev">‹</button>
            <button class="btn-circle" id="next">›</button>
        </div>
        @endif

        {{-- ================= ORDER BOX ================= --}}
        <div class="order-section text-center mt-4">

            {{-- QUANTITY --}}
            <div class="d-flex justify-content-center align-items-center gap-3 mb-3">
                <button class="btn btn-secondary" id="minus">-</button>

                <input type="number"
                       id="quantity"
                       value="1"
                       min="1"
                       style="width:60px;text-align:center;">

                <button class="btn btn-secondary" id="plus">+</button>
            </div>

            {{-- NAME --}}
            <h5 id="product-name" class="fw-bold mb-2">
                {{ $products->first()->name ?? 'Produk belum tersedia' }}
            </h5>

            {{-- DESCRIPTION --}}
            <p id="product-description"
               class="text-muted small mb-2 px-3"
               style="min-height:48px;">
                {{ $products->first() && $products->first()->description
                    ? Str::limit(strip_tags($products->first()->description), 140, '...')
                    : 'Deskripsi produk belum tersedia.' }}
            </p>

            {{-- PRICE --}}
            <p id="product-price" class="fw-semibold mb-3">
                @if($products->first())
                    Rp {{ number_format($products->first()->price) }}
                @else
                    Rp 0
                @endif
            </p>

            {{-- ACTION --}}
            @if($products->count() > 0 && ($products->first()->stock ?? 0) > 0)
                <button class="btn btn-danger px-4 py-2" id="order-btn">
                    Pesan Sekarang
                </button>
            @else
                <button class="btn btn-secondary px-4 py-2" disabled>
                    Stok Habis
                </button>
            @endif

        </div>
    </div>
</section>
</section>
