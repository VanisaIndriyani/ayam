@extends('layouts.admin')

@section('title', 'Edit Pengguna')

@section('content')

<h3 class="fw-bold mb-4">Edit Pengguna</h3>

<form action="{{ route('admin.users.update', $user->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="name" class="form-control"
               value="{{ $user->name }}" required>
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control"
               value="{{ $user->email }}" required>
    </div>

    <div class="mb-3">
        <label>Role</label>
        <select name="role" class="form-select">
            <option value="user" {{ $user->role=='user'?'selected':'' }}>User</option>
            <option value="admin" {{ $user->role=='admin'?'selected':'' }}>Admin</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Password (Opsional)</label>
        <input type="password" name="password" class="form-control"
               placeholder="Biarkan kosong jika tidak diubah">
    </div>

    <button class="btn btn-primary">Update</button>

</form>

@endsection
