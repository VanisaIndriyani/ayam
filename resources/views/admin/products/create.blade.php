@extends('layouts.admin')

@section('title', 'Tambah Produk')

@section('content')

<div class="container-fluid">

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">

            <h4 class="fw-bold mb-4">Tambah Produk Baru</h4>

            <form action="{{ route('admin.products.store') }}" 
                  method="POST" 
                  enctype="multipart/form-data"
                  class="row g-4">

                @csrf

                {{-- FORM --}}
                @include('admin.products.form')

                {{-- BUTTON --}}
                <div class="col-12 text-end mt-3">
                    <button class="btn btn-primary px-4">
                        <i class="bi bi-save me-1"></i> Simpan Produk
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection
