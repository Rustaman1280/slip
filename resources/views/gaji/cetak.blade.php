<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Slip Gaji - {{ $slip->no_slip }}</title>

    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: #1e293b;
        }
        .slip-container {
            max-width: 800px;
            margin: 30px auto;
            background: #fff;
            padding: 40px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
        }
        .slip-header {
            border-bottom: 2px solid #212529;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .table-rincian th {
            background-color: #f8f9fa;
            color: #212529;
        }
        .total-box {
            background-color: #f8f9fa;
            border: 1px solid #212529;
            padding: 12px;
            border-radius: 4px;
        }
        
        /* Gaya khusus saat dicetak / simpan ke PDF */
        @media print {
            body {
                background: none;
            }
            .slip-container {
                max-width: 100%;
                margin: 0;
                padding: 10px;
                border: none;
                box-shadow: none;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- Tombol Aksi di Atas (Disembunyikan saat dicetak) -->
    <div class="container text-center my-3 no-print">
        <div class="d-inline-flex gap-2">
            <button onclick="window.print()" class="btn btn-dark btn-sm">
                <i class="bi bi-printer me-1"></i> Cetak / Simpan PDF
            </button>
            <a href="{{ route('slip-gaji.show', $slip->id) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
        <div class="small text-muted mt-2">
            Tips: Pada kotak dialog cetak browser, pilih <strong>"Destination: Save as PDF"</strong> untuk mengunduh berkas PDF.
        </div>
    </div>

    <!-- Lembar Dokumen Slip Gaji Format Resmi Bersih & Netral -->
    <div class="slip-container">
        <!-- Header Dokumen Slip Gaji (Tanpa PT Sesuai Permintaan) -->
        <div class="slip-header text-center">
            <h4 class="fw-bold text-uppercase mb-1">SLIP GAJI KARYAWAN</h4>
            <p class="mb-0 small text-secondary">
                PERIODE : <span class="fw-semibold text-dark">{{ $slip->periode ?: date('d F Y', strtotime($slip->tanggal)) }}</span> &bull; No. Slip: {{ $slip->no_slip }}
            </p>
        </div>

        <!-- Identitas Karyawan -->
        <table class="table table-borderless table-sm mb-4">
            <tr>
                <td style="width: 18%;" class="fw-semibold text-secondary">Nama Karyawan</td>
                <td style="width: 2%;">:</td>
                <td style="width: 30%;" class="fw-bold text-dark">{{ $slip->nama_karyawan }}</td>

                <td style="width: 18%;" class="fw-semibold text-secondary">Periode Gaji</td>
                <td style="width: 2%;">:</td>
                <td style="width: 30%;">{{ $slip->periode ?: date('d F Y', strtotime($slip->tanggal)) }}</td>
            </tr>
            <tr>
                <td class="fw-semibold text-secondary">NIK</td>
                <td>:</td>
                <td>{{ $slip->nik }}</td>

                <td class="fw-semibold text-secondary">Jabatan</td>
                <td>:</td>
                <td>{{ $slip->jabatan }}</td>
            </tr>
            @if ($slip->no_telepon)
            <tr>
                <td class="fw-semibold text-secondary">No. Telepon</td>
                <td>:</td>
                <td colspan="4">{{ $slip->no_telepon }}</td>
            </tr>
            @endif
        </table>

        <!-- Tabel Rincian Penghasilan & Potongan -->
        <div class="row g-3 mb-4">
            <!-- Kolom Penghasilan -->
            <div class="col-6">
                <table class="table table-bordered table-sm table-rincian mb-0">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-uppercase text-center">I. Penghasilan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Gaji Pokok</td>
                            <td class="text-end">{{ \App\Models\SlipGaji::rupiah($slip->gaji_pokok) }}</td>
                        </tr>
                        <tr>
                            <td>Uang Lembur</td>
                            <td class="text-end">{{ \App\Models\SlipGaji::rupiah($slip->lembur) }}</td>
                        </tr>
                        <tr class="fw-bold bg-light">
                            <td>Total Penghasilan (A)</td>
                            <td class="text-end">{{ \App\Models\SlipGaji::rupiah($slip->total_penghasilan) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Kolom Potongan -->
            <div class="col-6">
                <table class="table table-bordered table-sm table-rincian mb-0">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-uppercase text-center">II. Potongan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Pinjaman Karyawan</td>
                            <td class="text-end">{{ \App\Models\SlipGaji::rupiah($slip->pinjaman) }}</td>
                        </tr>
                        <tr>
                            <td>Potongan Lain-lain</td>
                            <td class="text-end">Rp 0</td>
                        </tr>
                        <tr class="fw-bold bg-light">
                            <td>Total Potongan (B)</td>
                            <td class="text-end">{{ \App\Models\SlipGaji::rupiah($slip->total_potongan) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Box Total Gaji Bersih -->
        <div class="total-box text-center mb-4">
            <div class="small fw-semibold text-secondary text-uppercase">Gaji Bersih Diterima (Total Penghasilan - Total Potongan)</div>
            <h4 class="fw-bold text-dark mb-1 mt-1">
                {{ \App\Models\SlipGaji::rupiah($slip->gaji_bersih) }}
            </h4>
            <div class="small text-muted fst-italic">Terbilang: {{ $slip->terbilang }}</div>
        </div>

        @if ($slip->keterangan)
        <div class="mb-4 small text-muted">
            <em>Catatan: {{ $slip->keterangan }}</em>
        </div>
        @endif

        <!-- Tanda Tangan Penerima (Tanpa Bendahara Sesuai Permintaan) -->
        <div class="row mt-5 pt-3">
            <div class="col-12 text-end">
                <div class="d-inline-block text-center pe-4" style="min-width: 180px;">
                    <p class="mb-5 small text-secondary">Diterima oleh,</p>
                    <p class="fw-bold mb-0 text-decoration-underline text-dark">{{ $slip->nama_karyawan }}</p>
                    <small class="text-muted">NIK: {{ $slip->nik }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Cetak Otomatis saat Halaman Dibuka -->
    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>
