@extends('layouts.app')

@section('title', 'Lupa Password')

@section('content')

<div class="container py-5 d-flex justify-content-center">

    <div class="card shadow-sm border-0" style="max-width: 420px; width:100%;">
        <div class="card-body p-4">

            <h4 class="fw-bold mb-3">Lupa Password</h4>

            <p class="text-muted mb-4">
                Masukkan email Anda dan kami akan mengirimkan link reset password.
            </p>

            {{-- Session Status --}}
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                {{-- EMAIL --}}
                <div class="mb-3">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input id="email"
                           type="email"
                           name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}"
                           required autofocus>

                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- BUTTON --}}
                <div class="d-grid mt-4">
                    <button class="btn btn-primary">
                        <i class="bi bi-envelope-paper-heart-fill me-1"></i>
                        Kirim Link Reset Password
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection
