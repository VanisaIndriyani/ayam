<form method="post" action="{{ route('password.update') }}" class="password-form">
    @csrf
    @method('put')

    {{-- Password Lama --}}
    <div class="mb-3">
        <label class="form-label fw-semibold">Password Saat Ini</label>
        <input type="password" name="current_password"
               class="form-control form-control-lg" required>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">Password Baru</label>
            <input type="password" name="password"
                   class="form-control form-control-lg" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation"
                   class="form-control form-control-lg" required>
        </div>
    </div>

    {{-- Tombol Simpan --}}
    <div class="d-flex justify-content-end">
        <button class="btn btn-danger px-4 py-2">
            <i class="bi bi-shield-lock-fill me-1"></i> Simpan
        </button>
    </div>
</form>

<style>
.password-form input {
    border-radius: 10px;
}
</style>
