<nav class="neumorph-index navbar-expand-sm navbar bg-dark navbar-dark py-3 fixed-top">
    <div class="container-fluid">
        <div class="navbar-header-flex">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('images/logo1.png') }}" alt="Logo" class="img-fluid"
     style="max-height: 50px;">
                Bohri <span class="tahu">Farm</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="navmenu">
            <ul class="navbar-nav ms-auto align-items-center">
                <li><a href="#home" class="tempe2 text-white me-2 fs-5"><i class="bi bi-house-door-fill"></i></a></li>
                <li><a href="{{ route('login') }}" class="tempe2 text-white me-2 fs-5"><i class="bi bi-person-circle"></i></a></li>
                <li class="nav-item"><a href="#pageBeranda" class="tempe nav-link text-white">Beranda</a></li>
                <li class="nav-item"><a href="#pageProduk" class="tempe nav-link text-white">Produk</a></li>
                <li class="nav-item"><a href="#pageTentangKami" class="tempe nav-link text-white">Tentang Kami</a></li>
                <li class="nav-item"><a href="#pageHubungiKami" class="tempe nav-link text-white">Hubungi Kami</a></li>
            </ul>
        </div>
    </div>
</nav>
