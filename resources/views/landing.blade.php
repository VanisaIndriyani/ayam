<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bohri Farm</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('css/gaya.css') }}">

    {{-- Meta --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-logged-in" content="{{ auth()->check() ? '1' : '0' }}">
</head>

<body>

    {{-- ================= NAVBAR ================= --}}
    <nav class="neumorph-index navbar-expand-sm navbar bg-dark navbar-dark py-3 fixed-top">
        <div class="container-fluid">

            <div class="navbar-header-flex">
                <a class="navbar-brand" href="#home">
                    <img src="{{ asset('images/logo1.png') }}" alt="Logo" style="width:45px; height:auto;">
                    Bohri <span class="tahu">Farm</span>
                </a>

                {{-- Hamburger --}}
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            {{-- Menu --}}
            <div class="collapse navbar-collapse" id="navmenu">
                <ul class="navbar-nav ms-auto align-items-center">

                    <li>
                        <a href="#home" class="tempe2 text-white me-2 fs-5">
                            <i class="bi bi-house-door-fill"></i>
                        </a>
                    </li>

                    {{-- Login / User Icon --}}
                    @guest
                        {{-- Belum login --}}
                        <li class="nav-item me-2">
                            <a href="{{ route('login') }}" class="tempe2 text-white me-2 fs-5">
                                <i class="bi bi-person-circle"></i>
                            </a>
                        </li>
                    @else
                        {{-- Sudah login --}}
                        <li class="nav-item me-2">
                            <a href="{{ route('orders.index') }}" class="tempe2 text-white fs-5">
                                <i class="bi bi-person-check-fill"></i>
                            </a>
                        </li>

                        <li class="nav-item me-2">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="btn btn-sm btn-outline-light px-2 py-1"
                                        style="border-radius: 6px;">
                                    <i class="bi bi-box-arrow-right"></i>
                                    Keluar
                                </button>
                            </form>
                        </li>
                    @endguest

                    {{-- Navigasi --}}
                    <li class="nav-item"><a href="#pageBeranda" class="tempe nav-link text-white">Beranda</a></li>
                    <li class="nav-item"><a href="#pageProduk" class="tempe nav-link text-white">Produk</a></li>
                    <li class="nav-item"><a href="#pageTentangKami" class="tempe nav-link text-white">Tentang Kami</a></li>
                    <li class="nav-item"><a href="#pageHubungiKami" class="tempe nav-link text-white">Hubungi Kami</a></li>

                </ul>
            </div>

        </div>
    </nav>


    {{-- ================= HERO ================= --}}
    <section class="hero" id="home">
        <div class="hero-content container">
            <h1 class="display-4 fw-bold">Temukan Hasil Peternakan Terbaik</h1>

            <p class="lead mb-4">
                Ayam hidup, telur organik, dan produk unggulan langsung dari peternakan kami.
            </p>

            <div>
                <a href="#pageProduk" class="btn btn-custum-2 btn-danger btn-custom me-2 mb-2">Lihat Produk</a>

                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-custom mb-2">Login</a>
                @else
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-light btn-custom mb-2">Pesanan Saya</a>
                @endguest
            </div>
        </div>
    </section>


    {{-- ================== SECTIONS ================== --}}
    @include('sections.beranda')
    @include('sections.produk', ['products' => $products])
    @include('sections.tentang')
    @include('sections.hubungi')


    {{-- =================== JAVASCRIPT =================== --}}
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>
