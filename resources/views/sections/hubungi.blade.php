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