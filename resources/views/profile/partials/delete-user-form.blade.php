<section class="mb-5">

    {{-- HEADER --}}
    <header class="mb-4">
        <h4 class="fw-bold text-danger">Hapus Akun</h4>
        <p class="text-muted mb-0">
            Setelah akun Anda dihapus, semua data akan hilang secara permanen.
            Pastikan Anda telah menyimpan informasi penting sebelum melanjutkan.
        </p>
    </header>

    {{-- BUTTON TRIGGER MODAL --}}
    <button class="btn btn-danger px-4 py-2" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
        <i class="bi bi-trash me-1"></i> Hapus Akun
    </button>

    {{-- ===================== MODAL BOOTSTRAP ===================== --}}
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3">

                {{-- HEADER --}}
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold" id="deleteAccountModalLabel">
                        Konfirmasi Penghapusan Akun
                    </h5>
                    <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal"></button>
                </div>

                {{-- FORM --}}
                <form method="POST" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('DELETE')

                    <div class="modal-body">

                        <p class="text-muted mb-3">
                            Tindakan ini bersifat permanen dan tidak dapat dipulihkan.
                            Masukkan password Anda untuk mengonfirmasi penghapusan akun.
                        </p>

                        {{-- Password --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="delete_password">
                                Password
                            </label>

                            <input
                                type="password"
                                name="password"
                                id="delete_password"
                                class="form-control form-control-lg @error('password', 'userDeletion') is-invalid @enderror"
                                placeholder="Masukkan password Anda"
                                required
                            >

                            {{-- Error feedback --}}
                            @error('password', 'userDeletion')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>

                    {{-- FOOTER --}}
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-danger px-4">
                            <i class="bi bi-trash me-1"></i> Hapus Akun
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
    {{-- ===================== END MODAL ===================== --}}

</section>
