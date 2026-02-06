<div class="topbar d-flex justify-content-between align-items-center">

    {{-- TOGGLE (Mobile) --}}
    <span id="menuToggle" class="menu-toggle d-lg-none">
        <i class="bi bi-list"></i>
    </span>

    {{-- PAGE TITLE --}}
    <h5 class="fw-bold mb-0">
        @yield('title', 'Dashboard')
    </h5>

    {{-- USER SECTION --}}
    <div class="d-flex align-items-center gap-3">
        <span class="fw-semibold text-dark">{{ auth()->user()->name }}</span>

        {{-- Avatar --}}
        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=0D6EFD&color=fff"
             class="rounded-circle border shadow-sm"
             width="38" height="38" alt="avatar">

    </div>

</div>
