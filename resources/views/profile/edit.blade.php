@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')

<div class="container py-5" style="max-width: 1100px;">

    {{-- TITLE --}}
    <div class="mb-5 text-center">
        <h2 class="fw-bold mb-1">Profil Saya</h2>
        <p class="text-muted">Kelola informasi akun, password, dan keamanan.</p>
    </div>

    <div class="row g-4">

        {{-- PROFILE INFO --}}
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body p-4">

                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-person-circle me-1"></i> Informasi Profil
                    </h5>

                    @include('profile.partials.update-profile-information-form')

                </div>
            </div>
        </div>

        {{-- CHANGE PASSWORD --}}
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body p-4">

                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-shield-lock me-1"></i> Ubah Password
                    </h5>

                    @include('profile.partials.update-password-form')

                </div>
            </div>
        </div>

        {{-- DELETE ACCOUNT --}}
        <div class="col-lg-12">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">

                    <h5 class="fw-bold text-danger mb-3">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Hapus Akun
                    </h5>

                    <p class="text-muted mb-3" style="max-width: 500px;">
                        Setelah akun Anda dihapus, semua data akan hilang secara permanen.
                        Pastikan Anda sudah menyimpan informasi penting sebelum melanjutkan.
                    </p>

                    @include('profile.partials.delete-user-form')

                </div>
            </div>
        </div>

    </div>
</div>


{{-- CUSTOM STYLE --}}
<style>
    /* Biar card tidak terlalu tinggi dan kosong */
    .card {
        background: #ffffff;
    }

    /* Form elements */
    input.form-control, 
    textarea.form-control {
        border-radius: 8px;
        padding: 10px 14px;
    }

    /* Submit button spacing */
    .profile-submit-button {
        margin-top: 10px;
    }

    /* Mobile adjustments */
    @media (max-width: 768px) {
        .card {
            margin-bottom: 20px;
        }
    }
</style>

@endsection
