<div class="modal fade" id="mustLoginModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-3">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Masuk Dulu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center">
                <p class="text-muted mb-4">
                    Anda perlu login untuk melakukan pemesanan.
                </p>

                <a href="{{ route('login') }}" class="btn btn-danger w-100 mb-2">Login</a>
                <a href="{{ route('register') }}" class="btn btn-outline-secondary w-100">Daftar</a>
            </div>
        </div>
    </div>
</div>
