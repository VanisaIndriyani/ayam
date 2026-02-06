@extends('layouts.admin')

@section('title', 'Tambah Pengguna')

@section('content')

<h3 class="fw-bold mb-4">Tambah Pengguna</h3>

<form action="{{ route('admin.users.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Role</label>
        <select name="role" class="form-select">
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <button class="btn btn-primary">Simpan</button>
</form>

@endsection
