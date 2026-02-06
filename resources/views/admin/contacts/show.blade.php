@extends('layouts.admin')

@section('title', 'Detail Pesan Kontak')

@section('content')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">Detail Pesan Kontak</h3>

            <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="row g-4">

            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body">

                        <h5 class="fw-bold mb-3">Informasi Pengirim</h5>

                        <div class="mb-3">
                            <label class="fw-semibold text-muted">Nama Pengirim</label>
                            <p class="fs-5">{{ $contact->name }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="fw-semibold text-muted">Email</label>
                            <p class="fs-5">{{ $contact->email }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="fw-semibold text-muted">Nomor Telepon</label>
                            <p class="fs-5">{{ $contact->phone }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="fw-semibold text-muted">Jenis Pertanyaan</label>
                            <p class="fs-5 text-capitalize">{{ $contact->type }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="fw-semibold text-muted">Tanggal Dikirim</label>
                            <p class="fs-6">{{ $contact->created_at->format('d M Y H:i') }}</p>
                        </div>

                        <hr class="my-4">

                        <h5 class="fw-bold mb-3">Isi Pesan</h5>
                        <div class="p-3 rounded" style="background: #f7f7f7; border-left: 4px solid #0d6efd;">
                            <p class="mb-0 fs-6">{{ $contact->message }}</p>
                        </div>

                    </div>
                </div>
            </div>


            <div class="col-lg-4">

                {{-- Optional future features --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">

                        <h5 class="fw-bold mb-3">Aksi</h5>

                        <a href="mailto:{{ $contact->email }}?subject=Balasan dari Bohri Farm&body=Halo {{ $contact->name }},%0A%0A"
                            class="btn btn-success w-100 mb-3">
                            <i class="bi bi-reply-fill me-2"></i> Balas ke Email Pengirim
                        </a>

                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $contact->phone)) }}?text=Halo {{ $contact->name }}, kami dari Bohri Farm..."
                            class="btn btn-outline-primary w-100" target="_blank">
                            <i class="bi bi-whatsapp me-2"></i> Hubungi Pengirim (WA)
                        </a>

                    </div>
                </div>

            </div>

        </div>

    </div>

@endsection