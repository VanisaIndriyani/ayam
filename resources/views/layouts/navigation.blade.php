<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
    <div class="container">

        {{-- Brand / Logo --}}
        <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" height="34" class="me-2">
            {{ config('app.name', 'Laravel') }}
        </a>

        {{-- Hamburger --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#mainNavbar" aria-controls="mainNavbar"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Navbar Content --}}
        <div class="collapse navbar-collapse" id="mainNavbar">

            {{-- LEFT MENU --}}
            <ul class="navbar-nav me-auto">

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-semibold' : '' }}"
                        href="{{ route('dashboard') }}">
                        <i class="fa-solid fa-gauge me-1"></i> Dashboard
                    </a>
                </li>

            </ul>

            {{-- RIGHT MENU --}}
            <ul class="navbar-nav ms-auto">

                {{-- USER DROPDOWN --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown"
                       role="button" data-bs-toggle="dropdown" aria-expanded="false">

                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D6EFD&color=fff"
                             class="rounded-circle me-2" width="32" height="32">

                        <span>{{ Auth::user()->name }}</span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end mt-2 shadow-sm" aria-labelledby="userDropdown">

                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="fa-solid fa-user-gear me-2"></i> Profil
                            </a>
                        </li>

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

            </ul>
        </div>

    </div>
</nav>
