<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bohri Farm</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('css/gaya.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-logged-in" content="{{ auth()->check() ? '1' : '0' }}">

    @stack('css')
</head>

<body>

    {{-- ================= NAVBAR ================= --}}
    <nav class="neumorph-index navbar-expand-sm navbar bg-dark navbar-dark py-3 fixed-top">
        <div class="container-fluid">

            <div class="navbar-header-flex">
                <a class="navbar-brand" href="{{ url('/#home') }}">
                    <img src="{{ asset('images/logo1.png') }}" alt="Logo" style="width:45px; height:auto;">
                    Bohri <span class="tahu">Farm</span>
                </a>

                {{-- Hamburger --}}
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="navmenu">
                <ul class="navbar-nav ms-auto align-items-center">

                    <li>
                        <a href="{{ url('/#home') }}" class="tempe2 text-white me-2 fs-5">
                            <i class="bi bi-house-door-fill"></i>
                        </a>
                    </li>

                    {{-- LOGIN / USER --}}
                    @guest
                        <li class="nav-item me-2">
                            <a href="{{ route('login') }}" class="tempe2 text-white fs-5">
                                <i class="bi bi-person-circle"></i>
                            </a>
                        </li>
                    @else
                        {{-- Orders --}}
                        <li class="nav-item me-2">
                            <a href="{{ route('orders.index') }}" class="tempe2 text-white fs-5">
                                <i class="bi bi-person-check-fill"></i>
                            </a>
                        </li>

                        {{-- Logout --}}
                        <li class="nav-item me-2">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="tempe2 text-white fs-5" style="background:none; border:none; padding:0; padding-bottom:4px; border-bottom:2px solid transparent;" title="Keluar">
                                    <i class="bi bi-box-arrow-right"></i>
                                </button>
                            </form>
                        </li>
                    @endguest

                    {{-- Menu navigasi --}}
                    <li class="nav-item"><a href="{{ url('/#pageBeranda') }}" class="tempe nav-link text-white">Beranda</a></li>
                    <li class="nav-item"><a href="{{ url('/#pageProduk') }}" class="tempe nav-link text-white">Produk</a></li>
                    <li class="nav-item"><a href="{{ url('/#pageTentangKami') }}" class="tempe nav-link text-white">Tentang Kami</a></li>
                    <li class="nav-item"><a href="{{ url('/#pageHubungiKami') }}" class="tempe nav-link text-white">Hubungi Kami</a></li>

                </ul>
            </div>

        </div>
    </nav>

    {{-- ================= HERO ================= --}}
    <section class="hero" id="home" style="background: url('{{ asset('images/bg.jpeg') }}') center/cover no-repeat;">
        <div class="hero-content container">
            <h1 class="display-4 fw-bold">Temukan Hasil Peternakan Terbaik</h1>
            <p class="lead mb-4">
                Ayam hidup, telur organik, dan produk unggulan langsung dari peternakan kami.
            </p>

            <div>
                <a href="#pageProduk" class="btn btn-custum-2 btn-danger btn-custom me-2 mb-2">Lihat Produk</a>
                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-custom mb-2">Login</a>
                @else
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-light btn-custom mb-2">Pesanan Saya</a>
                @endguest
            </div>
        </div>
    </section>

    {{-- Halaman lain akan menimpa content --}}
    <div class="container mt-4" style="margin-top: 100px !important;">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    {{-- ============================================================
         SECTION: BERANDA (REVIEWS & FEATURES)
    ============================================================ --}}
    <section class="beranda" id="pageBeranda">
        <section class="container text-center my-3">
            <h3 class="fw-bold mb-4">Mengapa Memilih Bohri Farm</h3>
            <p class="text-muted mb-5">Kami menyediakan layanan luar biasa dan solusi untuk memastikan kebutuhan Anda.</p>
    
            <div class="row g-4">
    
                <div class="col-md-3 col-sm-6 col-12">
                    <i class="bi bi-trophy feature-icon"></i>
                    <h6 class="fw-semibold mt-3">Pengalaman selama 40 Tahun</h6>
                    <p class="text-muted small">
                        Berbekal 40 tahun pengalaman di bidang ayam, kami belajar bahwa kunci sukses adalah sabar,
                        kerja keras, dan pantang menyerah hingga kini dipercaya pelanggan di berbagai daerah.
                    </p>
                </div>
    
                <div class="col-md-3 col-sm-6 col-12">
                    <i class="bi bi-search feature-icon"></i>
                    <h6 class="fw-semibold mt-3">Apa saja yang ada di sini?</h6>
                    <p class="text-muted small">
                        Cari ayam, bebek? Semua ada di toko kami! Mulai dari ayam, ayam petelur,
                        ayam remaja, ayam kampung, anak bebek, telur ayam, telur bebek, empan ayam.
                    </p>
                </div>
    
                <div class="col-md-3 col-sm-6 col-12">
                    <i class="bi bi-cash-coin feature-icon"></i>
                    <h6 class="fw-semibold mt-3">Kemudahan Transaksi</h6>
                    <p class="text-muted small">
                        Belanja di toko kami makin praktis! Pilih ayam, ayam kampung, bebek, telur? Pembayaran melalui transfer. Cepat, aman, dan tanpa ribet!
                    </p>
                </div>
    
                <div class="col-md-3 col-sm-6 col-12">
                    <i class="bi bi-tag feature-icon"></i>
                    <h6 class="fw-semibold mt-3">Harga Langsung dari Peternakan</h6>
                    <p class="text-muted small">
                        Nikmati harga jujur langsung dari peternakan kami, tanpa perantara, tanpa mahal....Harga transparan
                        langsung dari sumbernya.
                    </p>
                </div>
    
            </div>
        </section>
    
        {{-- Apa kata pelanggan --}}
        <section class="container mt-section pb-4">
            <h4 class="text-center fw-bold mb-4">Apa Kata Pelanggan Kami</h4>
            <p class="text-center text-muted mb-5">
                Dengarkan dari pelanggan kami yang puas tentang pengalaman mereka dengan BohriFarm.
            </p>
    
            <div class="row g-4 mb-5">
                @forelse($reviews as $review)
                    <div class="col-md-4">
                        <div class="testimonial p-4 shadow-sm rounded bg-white h-100 position-relative">
                            <div class="mb-3 text-warning">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                @endfor
                            </div>
                            <p class="text-muted fst-italic mb-4">
                                "{{ $review->content }}"
                            </p>
                            <div class="d-flex align-items-center mt-auto">
                                <div class="avatar bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-person-fill text-secondary fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">{{ $review->user->name }}</h6>
                                    <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p class="text-muted">Belum ada ulasan saat ini.</p>
                    </div>
                @endforelse
            </div>
    
            @auth
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body p-4">
                                <h5 class="fw-bold text-center mb-4">Berikan Ulasan Anda</h5>
                                <form action="{{ route('reviews.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-3 text-center">
                                        <label class="form-label d-block">Rating</label>
                                        <div class="rating-input">
                                            <div class="btn-group" role="group" aria-label="Rating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <input type="radio" class="btn-check" name="rating" id="rating{{ $i }}" value="{{ $i }}" {{ $i == 5 ? 'checked' : '' }} autocomplete="off">
                                                    <label class="btn btn-outline-warning" for="rating{{ $i }}">{{ $i }} <i class="bi bi-star-fill"></i></label>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="content" class="form-label">Ulasan</label>
                                        <textarea class="form-control" id="content" name="content" rows="3" placeholder="Bagikan pengalaman Anda..." required></textarea>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-danger btn-custom px-4">Kirim Ulasan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center">
                    <p class="text-muted">Ingin memberikan ulasan? Silakan <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Login</a> terlebih dahulu.</p>
                </div>
            @endauth
        </section>
    </section>

    {{-- ============================================================
         SECTION: PRODUK
    ============================================================ --}}
    {{-- ==== PROTECTOR ==== --}}
    @if(isset($products) && $products !== null)
    
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
    @endif

    {{-- ============================================================
         SECTION: TENTANG KAMI
    ============================================================ --}}
    <section class="tentang" id="pageTentangKami">
    
        <section class="tentanghero">
            <div class="container">
                <h2 class="cycle fw-bold">Tentang BohriFarm</h2>
                <p>
                    Peternakan modern yang berkomitmen menghadirkan hasil terbaik dari alam untuk Anda.
                </p>
            </div>
        </section>
    
        <section class="container my-5">
            <div class="about-section row align-items-center g-4">
    
                <div class="col-md-6">
                    <img src="{{ asset('images/tentangkami.jpg') }}" loading="lazy" class="img-fluid" alt="Peternakan BohriFarm">
                </div>
    
                <div class="col-md-6">
                    <h3 class="fw-bold mb-3">Siapa Kami?</h3>
                    <p>
                        BohriFarm berdiri sejak tahun 1985 sebagai peternakan yang mengedepankan kualitas, kebersihan,
                        dan kesejahteraan hewan. Kami berkomitmen untuk menghadirkan produk hasil peternakan segar dan
                        bergizi tinggi, serta memberikan pelayanan terbaik bagi setiap pelanggan.
                    </p>
                    <p>
                        Kami percaya bahwa pertanian dan peternakan modern harus ramah lingkungan dan berkelanjutan —
                        karena dari alam, untuk manusia, dan kembali ke alam.
                    </p>
                </div>
    
            </div>
        </section>
    
        <section class="vision-mission text-center">
            <div class="container">
                <div class="row">
    
                    <div class="col-md-6 mb-4 mb-md-0">
                        <h3>Visi Kami</h3>
                        <p class="mt-3 text-white">
                            Menjadi peternakan modern terdepan di Indonesia yang menyediakan produk sehat, berkualitas,
                            dan berkelanjutan.
                        </p>
                    </div>
    
                    <div class="col-md-6">
                        <h3>Misi Kami</h3>
                        <ul class="list-unstyled mt-3 text-white">
                            <li>✔ Menjaga kualitas dan higienitas produk.</li>
                            <li>✔ Memberdayakan peternak lokal.</li>
                            <li>✔ Mengembangkan sistem peternakan ramah lingkungan.</li>
                            <li>✔ Memberikan pelayanan cepat dan profesional.</li>
                        </ul>
                    </div>
    
                </div>
            </div>
        </section>
    
        <section class="team container my-5 text-center">
            <h3 class="fw-bold mb-4">Alamat Kami</h3>
    
            <div class="ratio ratio-16x9">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.827704101184!2d106.78137957430341!3d-6.543429063966069!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69c3243d699b4f%3A0xeec60f4ae37358ed!2sBOHRI%20FARM!5e0!3m2!1sid!2sid!4v1764673894508!5m2!1sid!2sid"
                    style="border:0;" allowfullscreen loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
    
        </section>
    
    </section>

    {{-- ============================================================
         SECTION: HUBUNGI KAMI
    ============================================================ --}}
    <section class="hubungi" id="pageHubungiKami">
    
        <section class="container my-5">
    
            <div class="text-center mb-5">
                <h3 class="fw-bold">Hubungi Kami</h3>
                <p class="text-muted">
                    Punya pertanyaan seputar produk kami? Silakan isi formulir di bawah ini.
                </p>
            </div>
    
            {{-- FORM --}}
            <div class="form-section mb-5">
    
                <form action="{{ route('contact.store') }}" method="POST">
                    @csrf
    
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                    </div>
    
                    <div class="mb-3">
                        <label class="form-label">Alamat Email</label>
                        <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
                    </div>
    
                    <div class="mb-3">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" name="phone" class="form-control" placeholder="08xxxxxxxxxx" required>
                    </div>
    
                    <div class="mb-3">
                        <label class="form-label d-block">Jenis Pertanyaan</label>
    
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" value="Pertanyaan Umum" checked>
                            <label class="form-check-label">Pertanyaan Umum</label>
                        </div>
    
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" value="Penjualan">
                            <label class="form-check-label">Penjualan</label>
                        </div>
    
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" value="Jadwal Kunjungan">
                            <label class="form-check-label">Jadwal Kunjungan</label>
                        </div>
    
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" value="Kesehatan & Perawatan Ternak">
                            <label class="form-check-label">Kesehatan & Perawatan Ternak</label>
                        </div>
    
                    </div>
    
                    <div class="mb-3">
                        <label class="form-label">Pesan</label>
                        <textarea class="form-control" name="message" rows="4"
                            placeholder="Bagaimana kami bisa membantu Anda?" required></textarea>
                    </div>
    
                    <button type="submit" class="btn btn-primary px-4">Kirim Pesan</button>
                </form>
    
            </div>
    
            {{-- CONTACT INFO --}}
            <div class="contact-info">
    
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-danger">Kontak Kami</h2>
                    <p class="text-muted">Hubungi kami melalui informasi berikut.</p>
                </div>
    
                <div class="row text-center mb-4">
    
                    <div class="col-md-6 mb-3">
                        <i class="bi bi-telephone"></i>
                        <h6 class="fw-semibold mt-2">Telepon</h6>
                        <p>085891537692</p>
                    </div>
    
                    <div class="col-md-6 mb-3">
                        <i class="bi bi-envelope"></i>
                        <h6 class="fw-semibold mt-2">Email</h6>
                        <p>bohrifarm@gmail.com</p>
                    </div>
    
                </div>
    
                <hr>
    
                <div class="text-center">
                    <h6 class="fw-semibold mb-2">Jam Operasional</h6>
    
                    <p class="mb-1">Senin - Jumat: 06.00 - 17.00</p>
                    <p class="mb-1">Sabtu: 06.00 - 16.00</p>
                    <p>Minggu: 07.00 - 17.00</p>
                </div>
    
            </div>
        </section>
    
        {{-- Footer --}}
        <footer class="text-center">
            <div class="container">
                <p class="mb-0 fst-italic">Ready to be your business partner</p>
            </div>
        </footer>
    
    </section>

    {{-- Modal Login (optional) --}}
    @includeIf('components.modal-login')

    {{-- Modal Success (optional) --}}
    @includeIf('components.modal-success')

    {{-- JS --}}
    <script src="{{ asset('js/script.js') }}?v={{ time() }}"></script>

    @stack('scripts')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
