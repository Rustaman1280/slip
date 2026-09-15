<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Penggajian Karyawan')</title>

    <!-- Google Font: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar-custom {
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: -0.02em;
            color: #0f172a !important;
            font-size: 1.1rem;
        }

        .card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
            background: #ffffff;
        }

        .table > :not(caption) > * > * {
            padding: 0.85rem 0.75rem;
        }

        .table-hover > tbody > tr:hover {
            background-color: #f1f5f9;
        }

        .btn-dark-custom, .btn-primary {
            background-color: #0f172a;
            border-color: #0f172a;
            color: #ffffff;
        }

        .btn-dark-custom:hover, .btn-primary:hover, .btn-primary:focus {
            background-color: #334155;
            border-color: #334155;
            color: #ffffff;
        }

        .btn {
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.15s ease-in-out;
        }

        .form-control, .form-select {
            border-color: #cbd5e1;
            border-radius: 6px;
            padding: 0.55rem 0.75rem;
            color: #1e293b;
        }

        .form-control:focus, .form-select:focus {
            border-color: #334155;
            box-shadow: 0 0 0 2px rgba(51, 65, 85, 0.15);
        }

        .captcha-box {
            background-color: #0f172a;
            color: #f8fafc;
            font-size: 1.15rem;
            font-weight: 700;
            letter-spacing: 2px;
            padding: 0.5rem 1.25rem;
            border-radius: 6px;
            user-select: none;
            display: inline-block;
        }

        .box-highlight {
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 1.25rem;
        }

        /* Styling Cetak / Print Bersih */
        @media print {
            body {
                background: white !important;
                color: black !important;
                padding: 0 !important;
            }
            .navbar, .no-print, .btn, .alert {
                display: none !important;
            }
            .card {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
            }
            .col-lg-8, .col-lg-9, .col-lg-10 {
                width: 100% !important;
                max-width: 100% !important;
            }
            .box-highlight {
                background-color: #f8fafc !important;
                border: 1px solid #94a3b8 !important;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Navigasi Bar Minimalis & Konsisten (Hanya tampil jika bukan halaman login) -->
    @if (!request()->routeIs('login'))
    <nav class="navbar navbar-expand-lg navbar-custom py-2 mb-4 sticky-top no-print">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('slip-gaji.index') }}">
                <i class="bi bi-wallet2 text-dark fs-5"></i>
                <span>UKK PENGGAJIAN</span>
            </a>

            <div class="ms-auto d-flex align-items-center gap-3">
                @auth
                    <span class="small text-secondary d-none d-sm-inline">
                        <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->nama_lengkap ?? Auth::user()->username }}
                    </span>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Keluar</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                    </a>
                @endauth
            </div>
        </div>
    </nav>
    @endif

    <!-- Konten Utama -->
    <main class="container my-4 flex-grow-1">
        <!-- Notifikasi Pesan Sukses -->
        @if (session('success'))
            <div class="alert alert-light border border-secondary-subtle alert-dismissible fade show d-flex align-items-center rounded-2 py-2 px-3 mb-4" role="alert">
                <i class="bi bi-check-circle text-dark fs-6 me-2"></i>
                <div class="small text-dark fw-medium">{{ session('success') }}</div>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Notifikasi Pesan Error -->
        @if (session('error'))
            <div class="alert alert-light border border-danger-subtle alert-dismissible fade show d-flex align-items-center rounded-2 py-2 px-3 mb-4" role="alert">
                <i class="bi bi-exclamation-circle text-danger fs-6 me-2"></i>
                <div class="small text-danger fw-medium">{{ session('error') }}</div>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Notifikasi Error Validasi Input -->
        @if ($errors->any())
            <div class="alert alert-light border border-danger-subtle alert-dismissible fade show rounded-2 py-2 px-3 mb-4" role="alert">
                <div class="small text-danger fw-semibold mb-1"><i class="bi bi-x-circle me-1"></i> Periksa kembali data input Anda:</div>
                <ul class="mb-0 ps-3 small text-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>


    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
