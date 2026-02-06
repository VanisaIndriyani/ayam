@extends('layouts.app')

@section('title', 'Verifikasi Email')

@section('content')

<div class="container py-5 d-flex justify-content-center">

    <div class="card shadow-sm border-0" style="max-width: 480px; width:100%;">
        <div class="card-body p-4">

            <h4 class="fw-bold text-center mb-3">Verifikasi Email</h4>

            <p class="text-muted small mb-4 text-center">
                Terima kasih telah mendaftar!  
                Sebelum melanjutkan, mohon verifikasi alamat email Anda  
                dengan mengklik link yang baru saja kami kirimkan.
                <br><br>
                Jika Anda belum menerima email, Anda dapat meminta ulang.
            </p>

            {{-- SUCCESS MESSAGE --}}
            @if (session('status') == 'verification-link-sent')
                <div class="alert alert-success small">
                    Link verifikasi baru telah dikirim ke email yang Anda daftarkan.
                </div>
            @endif

            <div class="d-flex flex-column gap-3 mt-4">

                {{-- RESEND VERIFICATION EMAIL --}}
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-envelope-check me-1"></i>
                        Kirim Ulang Email Verifikasi
                    </button>
                </form>

                {{-- LOGOUT --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="btn btn-outline-secondary w-100">
                        <i class="bi bi-box-arrow-right me-1"></i>
                        Logout
                    </button>
                </form>

            </div>

        </div>
    </div>

</div>

@endsection
