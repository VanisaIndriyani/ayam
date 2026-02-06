@extends('layouts.app')

@section('title', 'Login')

@push('css')
<style>
    body {
        background: linear-gradient(135deg, #fdf0ee, #ffffff);
    }

    .auth-card {
        border-radius: 18px;
        padding: 32px;
        background: #ffffff;
        border: none;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }

    .brand-title {
        color: #B31312;
        font-weight: 700;
        font-size: 28px;
        margin-bottom: 8px;
    }

    .form-floating label {
        font-size: 14px;
        color: #7a7a7a;
    }

    .btn-bohrifarm {
        background-color: #B31312;
        border: none;
        border-radius: 8px;
        padding: 10px 18px;
        font-weight: 600;
    }

    .btn-bohrifarm:hover {
        background-color: #8e0f0d;
    }

    .auth-link {
        color: #B31312;
        font-weight: 500;
        text-decoration: none;
    }

    .auth-link:hover {
        color: #800d0c;
        text-decoration: underline;
    }
</style>
@endpush

@section('content')

<div class="container py-5 d-flex justify-content-center">

    <div class="auth-card" style="width: 420px;">

        {{-- Logo / Brand --}}
        <div class="text-center mb-3">
            <div class="brand-title">
                <i class="fa-solid fa-seedling"></i> Bohrifarm
            </div>
            <p class="text-muted">Masuk untuk melanjutkan belanja produk segar</p>
        </div>

        {{-- STATUS MESSAGE --}}
        @if (session('status'))
            <div class="alert alert-success mb-3">
                {{ session('status') }}
            </div>
        @endif

        {{-- FORM LOGIN --}}
        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- EMAIL --}}
            <div class="form-floating mb-3">
                <input type="email" id="email" name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       placeholder="Masukkan email"
                       value="{{ old('email') }}" required autofocus>

                <label for="email">Email</label>

                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- PASSWORD --}}
            <div class="form-floating mb-3">
                <input type="password" id="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Masukkan password" required>

                <label for="password">Password</label>

                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- REMEMBER ME --}}
            <div class="d-flex align-items-center mb-3">
                <input type="checkbox" id="remember" name="remember" class="me-2">
                <label for="remember" class="small text-secondary">Ingat saya</label>
            </div>

            {{-- SUBMIT BUTTON --}}
            <button class="btn btn-bohrifarm w-100 mb-3">
                <i class="fa-solid fa-right-to-bracket me-2"></i>
                Login
            </button>

            {{-- LINKS --}}
            <div class="text-center">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="auth-link small d-block">
                        Lupa password?
                    </a>
                @endif

                <span class="text-muted">Belum punya akun?</span>
                <a href="{{ route('register') }}" class="auth-link">
                    Daftar
                </a>
            </div>
        </form>

    </div>

</div>

@endsection
