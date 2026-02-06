@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')

<div class="container py-5 d-flex justify-content-center">

    <div class="card shadow-sm border-0" style="max-width: 460px; width:100%;">
        <div class="card-body p-4">

            <h4 class="fw-bold mb-3 text-center">Reset Password</h4>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                {{-- TOKEN --}}
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                {{-- EMAIL --}}
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email"
                           type="email"
                           name="email"
                           value="{{ old('email', $request->email) }}"
                           class="form-control @error('email') is-invalid @enderror"
                           required autofocus autocomplete="username">

                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- PASSWORD --}}
                <div class="mb-3">
                    <label for="password" class="form-label">Password Baru</label>
                    <input id="password"
                           type="password"
                           name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           required autocomplete="new-password">

                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- CONFIRM PASSWORD --}}
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input id="password_confirmation"
                           type="password"
                           name="password_confirmation"
                           class="form-control @error('password_confirmation') is-invalid @enderror"
                           required autocomplete="new-password">

                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- ACTION --}}
                <div class="d-grid mt-4">
                    <button class="btn btn-primary py-2">
                        <i class="bi bi-shield-lock-fill me-1"></i>
                        Reset Password
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection
