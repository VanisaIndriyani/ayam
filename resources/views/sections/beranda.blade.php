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
