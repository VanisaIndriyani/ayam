@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')

@section('content')

<div class="d-flex justify-content-between mb-4">
    <h3 class="fw-bold">Daftar Pengguna</h3>

    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Pengguna
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card shadow-sm">
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No. HP</th>
                        <th>Alamat</th>
                        <th>Role</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $loop->iteration + ($users->currentPage()-1)*$users->perPage() }}</td>

                        <td>{{ $user->name }}</td>

                        <td>{{ $user->email }}</td>

                        <td>{{ $user->phone ?? '-' }}</td>
                        <td>{{ Str::limit($user->address, 30) }}</td>

                        <td>
                            <span class="badge {{ $user->role == 'admin' ? 'bg-success' : 'bg-primary' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>

                        <td>{{ $user->created_at->format('d M Y') }}</td>

                        <td>
                            <a href="{{ route('admin.users.edit', $user->id) }}"
                               class="btn btn-sm btn-warning">
                                Edit
                            </a>

                            <form action="{{ route('admin.users.destroy', $user->id) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Hapus pengguna ini?')">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>

                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        <div class="mt-3">
            {{ $users->links('pagination::bootstrap-5') }}
        </div>

    </div>
</div>

@endsection
