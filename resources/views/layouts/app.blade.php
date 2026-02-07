<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ url('/') }}">
    <title>@yield('title') - Bohrifarm</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    {{-- Custom --}}
    <link rel="stylesheet" href="{{ asset('css/gaya.css') }}?v={{ time() }}">

    <style>
        /* Improve readability */
        body {
            background-color: #f8fafc;
        }

        /* Navbar Styling */
        .navbar {
            background-color: #B31312;
        }

        .navbar-brand {
            font-size: 1.4rem;
        }

        .navbar-nav .nav-link {
            font-weight: 500;
            padding-left: 16px !important;
            padding-right: 16px !important;
            color: #fff !important;
            transition: 0.2s;
        }

        .navbar-nav .nav-link.active,
        .navbar-nav .nav-link:hover {
            color: #ffe5e5 !important;
            text-decoration: underline;
        }

        /* Dropdown icon spacing */
        .dropdown-menu i {
            width: 18px;
        }

        /* Footer */
        footer {
            background-color: #B31312;
        }
    </style>

    @stack('css')
</head>

<body>
    <div class="d-flex flex-column min-vh-100">

    {{-- ================= NAVBAR ================= --}}
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
        <div class="container">

            {{-- BRAND --}}
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">
                <i class="fa-solid fa-seedling me-1"></i> Bohrifarm
            </a>

            {{-- TOGGLER --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            {{-- NAV --}}
            <div class="collapse navbar-collapse" id="navbarNav">

                {{-- LEFT MENU --}}
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/#pageBeranda') }}">Beranda</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/#pageProduk') }}">Produk</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/#pageTentangKami') }}">Tentang Kami</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/#pageHubungiKami') }}">Hubungi Kami</a>
                    </li>
                </ul>

                {{-- RIGHT MENU --}}
                <ul class="navbar-nav ms-auto align-items-center">

                    {{-- Cart (User Only) --}}
                    @auth
                        @if(auth()->user()->role === 'user')
                            <li class="nav-item me-2">
                                <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                                    <i class="fa-solid fa-cart-shopping fa-lg"></i>

                                    {{-- Optional badge --}}
                                    @if(session('cart_count') > 0)
                                        <span class="badge bg-warning text-dark position-absolute top-0 start-100 translate-middle">
                                            {{ session('cart_count') }}
                                        </span>
                                    @endif
                                </a>
                            </li>
                        @endif
                    @endauth

                    {{-- Guest Menu --}}
                    @guest
                        <li class="nav-item">
                            <a class="btn btn-light text-danger fw-semibold px-3"
                               href="{{ route('login') }}">
                                Login
                            </a>
                        </li>
                    @else

                        {{-- Admin Button --}}
                        @if(auth()->user()->role === 'admin')
                            <li class="nav-item me-2">
                                <a href="{{ route('admin.dashboard') }}"
                                   class="btn btn-sm btn-warning fw-bold px-3 py-2">
                                    <i class="fa-solid fa-gauge-high me-1"></i> Admin
                                </a>
                            </li>
                        @endif

                        {{-- User Dropdown --}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#"
                               role="button" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-user me-1"></i>
                                {{ auth()->user()->name }}
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end">

                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="fa-solid fa-id-card me-2"></i> Profil
                                    </a>
                                </li>

                                @if(auth()->user()->role === 'user')
                                    <li>
                                        <a class="dropdown-item" href="{{ route('orders.index') }}">
                                            <i class="fa-solid fa-receipt me-2"></i> Pesanan Saya
                                        </a>
                                    </li>
                                @endif

                                <li><hr class="dropdown-divider"></li>

                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item text-danger">
                                            <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>

                    @endguest
                </ul>

            </div>
        </div>
    </nav>

    {{-- =================== PAGE CONTENT =================== --}}
    <main class="py-4 flex-grow-1">
        @yield('content')
    </main>

    {{-- =================== FOOTER =================== --}}
    <footer class="text-center text-white py-3 mt-auto">
        <small>© {{ date('Y') }} Bohrifarm. All Rights Reserved.</small>
    </footer>

    </div> {{-- End d-flex wrapper --}}

    {{-- JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('js/script.js') }}?v={{ time() }}"></script>

    @stack('scripts')
</body>
</html>
