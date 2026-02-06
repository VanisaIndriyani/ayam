<nav class="sidebar d-flex flex-column">

    {{-- BRAND --}}
    <div class="d-flex align-items-center mb-4 px-2">
        <i class="bi bi-shop fs-3 text-primary me-2"></i>
        <span class="fs-4 fw-bold">Admin Panel</span>
    </div>

    {{-- MENU LIST --}}
    <ul class="nav nav-pills flex-column gap-1">

        {{-- Dashboard --}}
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}"
               class="nav-link d-flex align-items-center 
               {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-light' }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>

        {{-- Produk --}}
        <li class="nav-item">
            <a href="{{ route('admin.products.index') }}"
               class="nav-link d-flex align-items-center 
               {{ request()->routeIs('admin.products.*') ? 'active' : 'text-light' }}">
                <i class="bi bi-box-seam me-2"></i> Produk
            </a>
        </li>
        {{-- Kategori Produk --}}
        <li class="nav-item">
            <a href="{{ route('admin.categories.index') }}"
               class="nav-link d-flex align-items-center 
               {{ request()->routeIs('admin.categories.*') ? 'active' : 'text-light' }}">
                <i class="bi bi-tags me-2"></i> Kategori
            </a>
        </li>
        {{-- Pesanan --}}
        <li class="nav-item">
            <a href="{{ route('admin.orders.index') }}"
               class="nav-link d-flex align-items-center 
               {{ request()->routeIs('admin.orders.*') ? 'active' : 'text-light' }}">
                <i class="bi bi-receipt-cutoff me-2"></i> Pesanan
            </a>
        </li>
        {{-- Pembayaran --}}
        <li class="nav-item">
            <a href="{{ route('admin.payments.index') }}"
               class="nav-link d-flex align-items-center 
               {{ request()->routeIs('admin.payments.*') ? 'active' : 'text-light' }}">
                <i class="bi bi-credit-card me-2"></i> Pembayaran
            </a>
        </li>
        {{-- Users --}}
        <li class="nav-item">
            <a href="{{ route('admin.users.index') }}"
               class="nav-link d-flex align-items-center 
               {{ request()->routeIs('admin.users.*') ? 'active' : 'text-light' }}">
                <i class="bi bi-people me-2"></i> Pengguna
            </a>
        </li>
         {{-- Reports --}}
        <li class="nav-item">
            <a href="{{ route('admin.reports.index') }}"
               class="nav-link d-flex align-items-center 
               {{ request()->routeIs('admin.reports.*') ? 'active' : 'text-light' }}">
                <i class="bi bi-graph-up me-2"></i> Laporan
            </a>
        </li>      
        {{-- Kontak --}}
        <li class="nav-item">
            <a href="{{ route('admin.contacts.index') }}"
            class="nav-link d-flex align-items-center 
            {{ request()->routeIs('admin.contacts.*') ? 'active' : 'text-light' }}">
                <i class="bi bi-envelope-open me-2"></i> Kontak Masuk
            </a>
        </li>

    </ul>

    {{-- FOOTER --}}
    <div class="mt-auto pt-4">
        <form action="{{ route('logout') }}" method="POST" class="px-2">
            @csrf
            <button class="btn btn-danger w-100 d-flex align-items-center justify-content-center shadow-sm">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </button>
        </form>
    </div>

</nav>
