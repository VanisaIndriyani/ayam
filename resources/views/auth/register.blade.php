@extends('layouts.app')

@section('title', 'Daftar Akun')

@push('css')
<style>
    body {
        background: linear-gradient(135deg, #fdf0ee, #fff);
    }

    .auth-card {
        border-radius: 18px;
        padding: 32px;
        background: #ffffff;
        border: none;
        box-shadow: 0 10px 28px rgba(0, 0, 0, 0.08);
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
            <p class="text-muted">Daftar untuk mulai belanja produk segar</p>
        </div>

        {{-- FORM REGISTER --}}
        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- NAME --}}
            <div class="form-floating mb-3">
                <input type="text" name="name" id="name"
                       class="form-control @error('name') is-invalid @enderror"
                       placeholder="Nama lengkap" required value="{{ old('name') }}">

                <label for="name">Nama Lengkap</label>

                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- EMAIL --}}
            <div class="form-floating mb-3">
                <input type="email" name="email" id="email"
                       class="form-control @error('email') is-invalid @enderror"
                       placeholder="Alamat email" required value="{{ old('email') }}">

                <label for="email">Email</label>

                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- PASSWORD --}}
            <div class="form-floating mb-3">
                <input type="password" name="password" id="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Password" required>

                <label for="password">Password</label>

                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- CONFIRM PASSWORD --}}
            <div class="form-floating mb-4">
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="form-control @error('password_confirmation') is-invalid @enderror"
                       placeholder="Konfirmasi Password" required>

                <label for="password_confirmation">Konfirmasi Password</label>

                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- SUBMIT BUTTON --}}
            <button class="btn btn-bohrifarm w-100 mb-3">
                <i class="fa-solid fa-user-plus me-2"></i>
                Daftar Sekarang
            </button>

            {{-- LOGIN LINK --}}
            <div class="text-center">
                <span class="text-muted">Sudah punya akun?</span>
                <a href="{{ route('login') }}" class="auth-link">Masuk</a>
            </div>

        </form>

    </div>

</div>

@endsection
