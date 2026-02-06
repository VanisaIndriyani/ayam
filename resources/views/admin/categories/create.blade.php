@extends('layouts.admin')

@section('title', 'Tambah Kategori')

@section('content')
<div class="container" style="max-width: 600px;">

    <h3 class="fw-bold mb-4">Tambah Kategori</h3>

    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Parent Kategori (Opsional)</label>
            <select class="form-select" name="parent_id">
                <option value="">-- Tidak Ada --</option>
                @foreach($parents as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Kembali</a>

    </form>

</div>
@endsection
