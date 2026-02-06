@extends('layouts.admin')

@section('title', 'Kategori Produk')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between mb-4">
        <h3 class="fw-bold">Kategori Produk</h3>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Tambah Kategori
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <table class="table mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama</th>
                    <th>Slug</th>
                    <th>Parent</th>
                    <th width="160">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $cat)
                <tr>
                    <td>{{ $cat->name }}</td>
                    <td>{{ $cat->slug }}</td>
                    <td>{{ $cat->parent_id ? $cat->parent->name : '-' }}</td>

                    <td>
                        <a href="{{ route('admin.categories.edit', $cat->id) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>

                        <form action="{{ route('admin.categories.destroy', $cat->id) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="p-3">
            {{ $categories->links() }}
        </div>
    </div>

</div>
@endsection
