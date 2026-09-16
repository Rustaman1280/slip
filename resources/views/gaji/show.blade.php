@extends('layouts.app')

@section('title', 'Rincian Slip Gaji - ' . $slip->no_slip)

@section('content')
<div class="row justify-content-center mb-5">
    <div class="col-lg-8">
        <!-- tombol navigasi balik dan 3 tombol aksi -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2 no-print">
            <a href="{{ route('slip-gaji.index') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
                <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
            </a>

            <div class="d-flex flex-wrap gap-2">
                <!-- tombol kirim ke wa -->
                <a href="{{ $slip->wa_url }}" target="_blank" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
                    <i class="bi bi-whatsapp"></i> WhatsApp
                </a>

                <!-- tombol kirim ke gmail web -->
                <a href="{{ $slip->email_url }}" target="_blank" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
                    <i class="bi bi-envelope"></i> Gmail
                </a>

                <!-- tombol edit slip -->
                <a href="{{ route('slip-gaji.edit', $slip->id) }}" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1">
                    <i class="bi bi-pencil"></i> Edit Slip
                </a>

                <!-- tombol print slip atau simpan pdf -->
                <button type="button" onclick="window.print()" class="btn btn-dark-custom btn-sm d-inline-flex align-items-center gap-1">
                    <i class="bi bi-printer"></i> Cetak / Simpan PDF
                </button>
            </div>
        </div>

        <!-- kartu rincian slip gaji -->
        <div class="card p-4">
            <!-- judul slip gaji -->
            <div class="text-center border-bottom pb-3 mb-3">
                <h5 class="fw-bold text-dark text-uppercase mb-1">SLIP GAJI KARYAWAN</h5>
                <p class="text-secondary small mb-0">
                    PERIODE : <span class="fw-medium text-dark">{{ $slip->periode ?: date('d F Y', strtotime($slip->tanggal)) }}</span> &bull; No. Dokumen: {{ $slip->no_slip }}
                </p>
            </div>

            <!-- data diri karyawan -->
            <div class="row g-2 mb-4 p-3 bg-light rounded-2">
                <div class="col-sm-6">
                    <div class="small text-secondary">Nama Karyawan</div>
                    <div class="fw-semibold text-dark">{{ $slip->nama_karyawan }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="small text-secondary">Periode Gaji</div>
                    <div class="fw-semibold text-dark">{{ $slip->periode ?: date('d F Y', strtotime($slip->tanggal)) }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="small text-secondary">NIK</div>
                    <div class="fw-semibold text-dark">{{ $slip->nik }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="small text-secondary">Jabatan</div>
                    <div class="fw-semibold text-dark">{{ $slip->jabatan }}</div>
                </div>
                @if ($slip->no_telepon)
                <div class="col-sm-6">
                    <div class="small text-secondary">No. Telepon / WhatsApp</div>
                    <div class="fw-semibold text-dark">{{ $slip->no_telepon }}</div>
                </div>
                @endif
                @if ($slip->keterangan)
                <div class="col-sm-6">
                    <div class="small text-secondary">Keterangan</div>
                    <div class="fw-semibold text-dark">{{ $slip->keterangan }}</div>
                </div>
                @endif
            </div>

            <!-- rincian penghasilan sama potongan -->
            <div class="row g-3 mb-4">
                <!-- kolom penghasilan -->
                <div class="col-sm-6">
                    <div class="card border-0 bg-light p-3 h-100">
                        <span class="small fw-semibold text-secondary text-uppercase d-block mb-2">Penghasilan (A)</span>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="small text-secondary">Gaji Pokok</span>
                            <span class="small fw-medium text-dark">{{ \App\Models\SlipGaji::rupiah($slip->gaji_pokok) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="small text-secondary">Uang Lembur</span>
                            <span class="small fw-medium text-dark">{{ \App\Models\SlipGaji::rupiah($slip->lembur) }}</span>
                        </div>
                        <hr class="my-2 text-secondary">
                        <div class="d-flex justify-content-between fw-semibold">
                            <span class="small text-dark">Total Penghasilan</span>
                            <span class="small text-dark">{{ \App\Models\SlipGaji::rupiah($slip->total_penghasilan) }}</span>
                        </div>
                    </div>
                </div>

                <!-- kolom potongan -->
                <div class="col-sm-6">
                    <div class="card border-0 bg-light p-3 h-100">
                        <span class="small fw-semibold text-secondary text-uppercase d-block mb-2">Potongan (B)</span>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="small text-secondary">Pinjaman Karyawan</span>
                            <span class="small fw-medium text-dark">{{ \App\Models\SlipGaji::rupiah($slip->pinjaman) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="small text-secondary">Potongan Lain</span>
                            <span class="small fw-medium text-dark">Rp 0</span>
                        </div>
                        <hr class="my-2 text-secondary">
                        <div class="d-flex justify-content-between fw-semibold">
                            <span class="small text-dark">Total Potongan</span>
                            <span class="small text-dark">{{ \App\Models\SlipGaji::rupiah($slip->total_potongan) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- total gaji bersih dan terbilang -->
            <div class="box-highlight text-center mb-4">
                <span class="small text-secondary fw-semibold d-block mb-1">TOTAL GAJI BERSIH DITERIMA (A - B)</span>
                <h3 class="fw-bold text-dark mb-1">{{ \App\Models\SlipGaji::rupiah($slip->gaji_bersih) }}</h3>
                <div class="small text-muted fst-italic">Terbilang: {{ $slip->terbilang }}</div>
            </div>

            <!-- tanda tangan penerima di sebelah kanan -->
            <div class="row pt-4 mt-2">
                <div class="col-6"></div>
                <div class="col-6 text-center">
                    <p class="small text-secondary mb-1">Tanggal Terima, {{ date('d F Y', strtotime($slip->tanggal)) }}</p>
                    <p class="small text-secondary mb-5">Penerima / Karyawan,</p>
                    <p class="fw-bold text-dark mb-0 text-decoration-underline">( {{ $slip->nama_karyawan }} )</p>
                    <small class="text-muted">NIK: {{ $slip->nik }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
