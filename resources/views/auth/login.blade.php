@extends('layouts.app')

@section('title', 'Masuk - UKK Penggajian')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 75vh;">
    <div class="col-md-5 col-lg-4">
        <div class="card p-4 p-sm-5 shadow-sm">
            <!-- judul form login -->
            <div class="text-center mb-4">
                <h4 class="fw-bold text-dark mb-1 tracking-tight">Masuk</h4>
                <p class="text-muted small mb-0">Silakan masukkan username dan password</p>
            </div>

            <!-- info akun buat ngetes -->
            <div class="alert alert-secondary py-2 px-3 small border-0 mb-4 bg-light text-secondary d-flex align-items-center gap-2">
                <i class="bi bi-info-circle fs-6"></i>
                <span>Akun Uji: <strong>admin</strong> / <strong>admin123</strong></span>
            </div>

            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                <!-- input buat username -->
                <div class="mb-3">
                    <label for="username" class="form-label small fw-semibold text-secondary">Username</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control border-start-0" id="username" name="username" value="{{ old('username', 'admin') }}" required autofocus placeholder="Masukkan username">
                    </div>
                </div>

                <!-- input buat password -->
                <div class="mb-4">
                    <label for="password" class="form-label small fw-semibold text-secondary">Kata Sandi</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control border-start-0 border-end-0" id="password" name="password" value="admin123" required placeholder="Masukkan kata sandi">
                        <button class="btn btn-outline-secondary border-start-0 bg-light text-muted" type="button" id="btnTogglePassword" onclick="togglePassword()">
                            <i class="bi bi-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- tombol login -->
                <button type="submit" class="btn btn-dark-custom w-100 py-2">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Masuk ke Sistem
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // fungsi buat liat atau sembunyiin password pas diklik matanya
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('bi-eye');
            toggleIcon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('bi-eye-slash');
            toggleIcon.classList.add('bi-eye');
        }
    }
</script>
@endsection
