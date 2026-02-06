@extends('layouts.admin')

@section('title', 'Daftar Produk')

@push('styles')
<style>
    .pagination svg {
        width: 18px !important;
        height: 18px !important;
    }

    .pagination-wrapper nav {
        display: flex !important;
        justify-content: center !important;
    }

    table.table td,
    table.table th {
        vertical-align: middle !important;
        padding: 12px 10px !important;
    }

    table.table td:last-child {
        width: 160px;
        white-space: nowrap;
    }
</style>
@endpush

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Daftar Produk</h4>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Tambah Produk
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th style="width: 80px;">Foto</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($products as $p)
                        <tr>

                            <td>
                                @if($p->images->count() > 0)
                                    <img src="{{ asset('storage/' . $p->images->first()->path) }}" 
                                         alt="Img" 
                                         class="rounded" 
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <span class="text-muted small">No Image</span>
                                @endif
                            </td>

                            <td class="text-truncate" style="max-width: 200px;">{{ $p->name }}</td>

                            <td>{{ $p->category?->name ?? '-' }}</td>

                            <td>Rp {{ number_format($p->price) }}</td>

                            <td>{{ $p->stock }}</td>

                            <td>
                                <span class="badge {{ $p->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $p->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>

                            <td>
                                <div class="d-flex gap-2">

                                    <a href="{{ route('admin.products.edit', $p->id) }}"
                                       class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('admin.products.destroy', $p->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Hapus produk ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

        </div>
    </div>

    <div class="mt-3 pagination-wrapper">
        {{ $products->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
    </div>

</div>

@endsection
