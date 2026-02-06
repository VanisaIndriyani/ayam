<form method="post" action="{{ route('profile.update') }}" class="profile-form">
    @csrf
    @method('patch')

    {{-- Nama --}}
    <div class="mb-3">
        <label class="form-label fw-semibold">Nama Lengkap</label>
        <input type="text" name="name"
               class="form-control form-control-lg"
               value="{{ old('name', $user->name) }}" required>
    </div>

    {{-- Email --}}
    <div class="mb-3">
        <label class="form-label fw-semibold">Email</label>
        <input type="email" name="email"
               class="form-control form-control-lg"
               value="{{ old('email', $user->email) }}" required>
    </div>

    {{-- Nomor HP --}}
    <div class="mb-3">
        <label class="form-label fw-semibold">Nomor HP</label>
        <input type="text" name="phone"
               class="form-control form-control-lg"
               value="{{ old('phone', $user->phone) }}">
    </div>

    {{-- Tombol Simpan --}}
    <div class="d-flex justify-content-end">
        <button class="btn btn-danger px-4 py-2">
            <i class="bi bi-save me-1"></i> Simpan
        </button>
    </div>
</form>

<style>
.profile-form input {
    border-radius: 10px;
}
</style>
