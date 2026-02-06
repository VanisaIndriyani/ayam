@extends('layouts.admin')

@section('title', 'Kontak Masuk')

@section('content')

<h3 class="mb-4">Kontak Masuk</h3>

<div class="card shadow-sm">
    <div class="card-body">

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Tipe</th>
                    <th>Tanggal</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                @foreach($messages as $msg)
                <tr>
                    <td>{{ $msg->name }}</td>
                    <td>{{ $msg->email }}</td>
                    <td>{{ ucfirst($msg->type) }}</td>
                    <td>{{ $msg->created_at->format('d M Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.contacts.show', $msg->id) }}" 
                           class="btn btn-sm btn-primary">
                            Detail
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>

        <div class="mt-3">
            {{ $messages->links() }}
        </div>

    </div>
</div>

@endsection
