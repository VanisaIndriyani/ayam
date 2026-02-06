<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bohri Farm')</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('css/gaya.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-logged-in" content="{{ auth()->check() ? '1' : '0' }}">
    <meta name="base-url" content="{{ url('/') }}">

    @stack('css')
</head>

<body>

    {{-- ================= NAVBAR ================= --}}
    <nav class="neumorph-index navbar-expand-sm navbar bg-dark navbar-dark py-3 fixed-top">
        <div class="container-fluid">

            <div class="navbar-header-flex">
                <a class="navbar-brand" href="{{ url('/#home') }}">
                    <img src="{{ asset('images/logo1.png') }}" alt="Logo" style="width:45px; height:auto;">
                    Bohri <span class="tahu">Farm</span>
                </a>

                {{-- Hamburger --}}
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="navmenu">
                <ul class="navbar-nav ms-auto align-items-center">

                    <li>
                        <a href="{{ url('/#home') }}" class="tempe2 text-white me-2 fs-5">
                            <i class="bi bi-house-door-fill"></i>
                        </a>
                    </li>

                    {{-- LOGIN / USER --}}
                    @guest
                        <li class="nav-item me-2">
                            <a href="{{ route('login') }}" class="tempe2 text-white fs-5">
                                <i class="bi bi-person-circle"></i>
                            </a>
                        </li>
                    @else
                        {{-- Orders --}}
                        <li class="nav-item me-2">
                            <a href="{{ route('orders.index') }}" class="tempe2 text-white fs-5">
                                <i class="bi bi-person-check-fill"></i>
                            </a>
                        </li>

                        {{-- Logout --}}
                        <li class="nav-item me-2">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-light px-2 py-1" style="border-radius: 6px;">
                                    <i class="bi bi-box-arrow-right"></i>
                                    Keluar
                                </button>
                            </form>
                        </li>
                    @endguest

                    {{-- Menu navigasi --}}
                    <li class="nav-item"><a href="{{ url('/#pageBeranda') }}" class="tempe nav-link text-white">Beranda</a></li>
                    <li class="nav-item"><a href="{{ url('/#pageProduk') }}" class="tempe nav-link text-white">Produk</a></li>
                    <li class="nav-item"><a href="{{ url('/#pageTentangKami') }}" class="tempe nav-link text-white">Tentang Kami</a></li>
                    <li class="nav-item"><a href="{{ url('/#pageHubungiKami') }}" class="tempe nav-link text-white">Hubungi Kami</a></li>

                </ul>
            </div>

        </div>
    </nav>

    {{-- ================= HERO REMOVED (Moved to welcome.blade.php inline) ================= --}}


    {{-- Halaman lain akan menimpa content --}}
    <div class="container mt-4" style="margin-top: 100px !important;">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    @yield('content')

    {{-- Default HOME SECTIONS --}}
    {{-- @include('sections.beranda') --}}

    {{-- PRODUK HANYA DIMUAT DI LANDING PAGE, BUKAN DI LAYOUT GLOBAL --}}
    {{-- @include('sections.produk') --}}

    {{-- @include('sections.tentang') --}}
    {{-- @include('sections.hubungi') --}}


    {{-- Modal Login (optional) --}}
    @includeIf('components.modal-login')

    {{-- Modal Success (optional) --}}
    @includeIf('components.modal-success')

    {{-- JS --}}
    <script src="{{ asset('js/script.js') }}"></script>

    @stack('scripts')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
