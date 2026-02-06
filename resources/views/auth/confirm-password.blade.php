@extends('layouts.app')

@section('title', 'Konfirmasi Password')

@section('content')

<div class="container py-5 d-flex justify-content-center">

    <div class="card shadow-sm border-0" style="max-width: 420px; width:100%;">
        <div class="card-body p-4">

            <h4 class="fw-bold mb-3">Konfirmasi Password</h4>

            <p class="text-muted mb-4">
                Area ini aman. Silakan masukkan password Anda untuk melanjutkan.
            </p>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                {{-- PASSWORD --}}
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>

                    <input id="password"
                           type="password"
                           name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           required autocomplete="current-password">

                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- BUTTON --}}
                <div class="d-grid mt-4">
                    <button class="btn btn-primary">
                        <i class="bi bi-shield-lock-fill me-1"></i>
                        Konfirmasi
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection
