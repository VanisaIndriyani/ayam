@extends('layouts.admin')

@section('title', 'Edit Produk')

@section('content')

<div class="container-fluid">

    {{-- CARD UTAMA --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">

            <h4 class="fw-bold mb-4">Edit Produk</h4>

            <form action="{{ route('admin.products.update', $product->id) }}"
                  method="POST" enctype="multipart/form-data"
                  class="row g-4">
                @csrf
                @method('PUT')

                {{-- FORM --}}
                @include('admin.products.form')

                <div class="col-12 text-end">
                    <button class="btn btn-primary px-4">
                        <i class="bi bi-save me-1"></i> Update Produk
                    </button>
                </div>
            </form>

        </div>
    </div>


    {{-- GAMBAR PRODUK --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">

            <h5 class="fw-bold mb-3">Gambar Produk</h5>

            @if ($product->images->count() == 0)
                <p class="text-muted">Belum ada gambar.</p>
            @else
                <div class="row g-3">
                    @foreach ($product->images as $img)
                        <div class="col-6 col-md-3 col-lg-2">

                            <div class="border rounded p-2 bg-light position-relative">

                                <img src="{{ asset('storage/'.$img->path) }}" 
                                     class="img-fluid rounded" 
                                     style="height: 150px; object-fit: cover; width: 100%;">

                                {{-- Jika perlu tombol delete gambar --}}
                                {{-- <form action="{{ route('admin.products.deleteImage', $img->id) }}" method="POST"
                                      class="position-absolute top-0 end-0 m-1">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger rounded-circle p-1">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </form> --}}

                            </div>

                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>

</div>

@endsection
