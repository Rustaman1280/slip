@extends('layouts.app')

@section('title', 'Edit Slip Gaji - ' . $slip->no_slip)

@section('content')
<div class="row justify-content-center mb-5">
    <div class="col-lg-9">
        <!-- tombol navigasi -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('slip-gaji.index') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
                <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
            </a>
            <a href="{{ route('slip-gaji.show', $slip->id) }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
                <i class="bi bi-eye"></i> Lihat Rincian
            </a>
        </div>

        <div class="card p-4 shadow-sm">
            <!-- judul form dan pilihan periode tanggal -->
            <div class="text-center border-bottom pb-3 mb-4">
                <div class="d-inline-block badge bg-light text-dark border px-3 py-1 mb-2 fw-semibold">
                    No. Dokumen: {{ $slip->no_slip }}
                </div>
                <h5 class="fw-bold text-dark text-uppercase mb-1">EDIT SLIP GAJI KARYAWAN</h5>
                <div class="d-inline-flex flex-column align-items-center mt-2">
                    <label class="small text-secondary fw-semibold mb-1">PERIODE GAJI (DARI - SAMPAI)</label>
                    <div class="d-flex align-items-center gap-2">
                        <input type="date" class="form-control form-control-sm text-center" id="periode_mulai" name="periode_mulai" form="formEditSlipGaji" value="{{ old('periode_mulai', $periodeMulai) }}" required style="width: 160px;">
                        <span class="text-muted small fw-semibold">s/d</span>
                        <input type="date" class="form-control form-control-sm text-center" id="periode_selesai" name="periode_selesai" form="formEditSlipGaji" value="{{ old('periode_selesai', $periodeSelesai) }}" required style="width: 160px;">
                    </div>
                </div>
            </div>

            <form action="{{ route('slip-gaji.update', $slip->id) }}" method="POST" id="formEditSlipGaji">
                @csrf
                @method('PUT')

                <!-- bagian input data karyawan -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="nama_karyawan" class="form-label small text-secondary">Nama Karyawan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_karyawan" name="nama_karyawan" value="{{ old('nama_karyawan', $slip->nama_karyawan) }}" placeholder="Contoh: Budi Santoso" required autofocus>
                    </div>

                    <div class="col-md-6">
                        <label for="nik" class="form-label small text-secondary">NIK <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nik" name="nik" value="{{ old('nik', $slip->nik) }}" placeholder="Contoh: 320101234567" required>
                    </div>

                    <div class="col-md-6">
                        <label for="jabatan" class="form-label small text-secondary">Jabatan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="jabatan" name="jabatan" value="{{ old('jabatan', $slip->jabatan) }}" placeholder="Contoh: Web Developer" required>
                    </div>

                    <div class="col-md-6">
                        <label for="no_telepon" class="form-label small text-secondary">No. Telepon / WhatsApp</label>
                        <input type="text" class="form-control" id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $slip->no_telepon) }}" placeholder="Contoh: 081234567890">
                    </div>
                </div>

                <!-- rincian penghasilan sama potongan -->
                <div class="row g-3 mb-4">
                    <!-- kolom penghasilan -->
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header bg-light border-bottom py-2 text-center small fw-semibold text-secondary">
                                PENGHASILAN
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="gaji_pokok" class="form-label small text-secondary">Gaji Pokok (Rp) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control hitung-gaji" id="gaji_pokok" name="gaji_pokok" value="{{ old('gaji_pokok', (int)$slip->gaji_pokok) }}" min="0" step="1000" placeholder="0" required>
                                </div>
                                <div class="mb-3">
                                    <label for="lembur" class="form-label small text-secondary">Lembur (Rp)</label>
                                    <input type="number" class="form-control hitung-gaji" id="lembur" name="lembur" value="{{ old('lembur', (int)$slip->lembur) }}" min="0" step="1000" placeholder="0">
                                </div>
                                <hr class="my-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="small fw-semibold text-secondary">Total Penghasilan:</span>
                                    <span class="fw-bold fs-6 text-dark" id="text_total_penghasilan">Rp 0</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- kolom potongan -->
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header bg-light border-bottom py-2 text-center small fw-semibold text-secondary">
                                RINCIAN POTONGAN
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="pinjaman" class="form-label small text-secondary">Pinjaman Karyawan (Rp)</label>
                                    <input type="number" class="form-control hitung-gaji" id="pinjaman" name="pinjaman" value="{{ old('pinjaman', (int)$slip->pinjaman) }}" min="0" step="1000" placeholder="0">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small text-secondary">Potongan Lain</label>
                                    <input type="text" class="form-control bg-light" value="Rp 0" disabled>
                                </div>
                                <hr class="my-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="small fw-semibold text-secondary">Total Potongan:</span>
                                    <span class="fw-bold fs-6 text-dark" id="text_total_potongan">Rp 0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- keterangan catatan opsional -->
                <div class="mb-4">
                    <label for="keterangan" class="form-label small text-secondary">Keterangan / Catatan (Opsional)</label>
                    <textarea class="form-control" id="keterangan" name="keterangan" rows="2" placeholder="Catatan tambahan bila ada">{{ old('keterangan', $slip->keterangan) }}</textarea>
                </div>

                <!-- kotak hasil total gaji bersih -->
                <div class="box-highlight text-center mb-4">
                    <span class="small text-secondary fw-semibold d-block mb-1">Total Gaji Bersih Diterima</span>
                    <h3 class="fw-bold text-dark mb-0" id="text_gaji_bersih">Rp 0</h3>
                </div>

                <!-- tombol aksi -->
                <div class="d-flex gap-2 justify-content-end">
                    <a href="{{ route('slip-gaji.index') }}" class="btn btn-outline-secondary px-3">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-dark-custom px-4 d-inline-flex align-items-center gap-2">
                        <i class="bi bi-check-lg"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // fungsi buat format angka jadi rupiah
    function formatRupiah(angka) {
        return 'Rp ' + Number(angka || 0).toLocaleString('id-ID');
    }

    // hitung total gaji otomatis pas ngetik angka
    function hitungGaji() {
        const gajiPokok = parseFloat(document.getElementById('gaji_pokok').value) || 0;
        const lembur = parseFloat(document.getElementById('lembur').value) || 0;
        const pinjaman = parseFloat(document.getElementById('pinjaman').value) || 0;

        // rumus: bersih = penghasilan - potongan
        const totalPenghasilan = gajiPokok + lembur;
        const totalPotongan = pinjaman;
        const gajiBersih = totalPenghasilan - totalPotongan;

        document.getElementById('text_total_penghasilan').innerText = formatRupiah(totalPenghasilan);
        document.getElementById('text_total_potongan').innerText = formatRupiah(totalPotongan);
        document.getElementById('text_gaji_bersih').innerText = formatRupiah(gajiBersih);
    }

    // dengerin event ngetik biar langsung update
    document.querySelectorAll('.hitung-gaji').forEach(function(element) {
        element.addEventListener('input', hitungGaji);
    });

    // jalankan sekali pas halaman pertama kali dibuka
    document.addEventListener('DOMContentLoaded', function() {
        hitungGaji();
    });
</script>
@endsection
