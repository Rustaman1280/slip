@extends('layouts.app')

@section('title', 'Riwayat Slip Gaji Karyawan')

@section('content')
<!-- Header Halaman Riwayat & Tombol Tambah Slip Gaji -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h5 class="fw-bold text-dark mb-1">Riwayat Slip Gaji Karyawan</h5>
        <p class="text-muted small mb-0">Daftar arsip slip gaji karyawan yang telah dibuat dan dihitung.</p>
    </div>
    <div>
        <!-- Tombol Tambah Slip Gaji Minimalis -->
        <a href="{{ route('slip-gaji.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
            <i class="bi bi-plus-lg fs-6"></i>
            <span>Tambah Slip Gaji</span>
        </a>
    </div>
</div>

<!-- 3 Kartu Ringkasan (Stats Cards) Minimalis -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small d-block mb-1">Total Dokumen Slip</span>
                    <h5 class="fw-bold text-dark mb-0">{{ $totalSlip }} Dokumen</h5>
                </div>
                <div class="text-secondary">
                    <i class="bi bi-file-earmark-text fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small d-block mb-1">Total Pengeluaran Gaji</span>
                    <h5 class="fw-bold text-dark mb-0">{{ \App\Models\SlipGaji::rupiah($totalPengeluaran) }}</h5>
                </div>
                <div class="text-secondary">
                    <i class="bi bi-cash fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small d-block mb-1">Rata-rata Gaji Bersih</span>
                    <h5 class="fw-bold text-dark mb-0">{{ \App\Models\SlipGaji::rupiah($rataRataGaji) }}</h5>
                </div>
                <div class="text-secondary">
                    <i class="bi bi-wallet2 fs-4"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Riwayat Slip Gaji Minimalis & Rapi -->
<div class="card">
    <div class="card-header bg-white py-3 border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
        <span class="fw-semibold text-dark small">Daftar Arsip Slip Gaji</span>
        <!-- Input Pencarian Sederhana -->
        <div class="input-group input-group-sm" style="max-width: 240px;">
            <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control border-start-0" id="searchTable" placeholder="Cari nama atau NIK...">
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tableSlipGaji">
                <thead class="table-light border-bottom">
                    <tr class="small text-secondary fw-semibold">
                        <th class="text-center" style="width: 45px;">No</th>
                        <th>No. Slip</th>
                        <th>Periode Gaji</th>
                        <th>Karyawan</th>
                        <th>Jabatan</th>
                        <th class="text-end">Penghasilan</th>
                        <th class="text-end">Potongan</th>
                        <th class="text-end">Gaji Bersih</th>
                        <th class="text-center" style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($daftarSlip as $index => $item)
                        <tr>
                            <td class="text-center text-muted small">{{ $index + 1 }}</td>
                            <td>
                                <span class="fw-semibold text-dark">{{ $item->no_slip }}</span>
                            </td>
                            <td class="text-secondary small">
                                {{ $item->periode ?: date('d/m/Y', strtotime($item->tanggal)) }}
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $item->nama_karyawan }}</div>
                                <small class="text-muted">NIK: {{ $item->nik }}</small>
                            </td>
                            <td>
                                <span class="small text-secondary">{{ $item->jabatan }}</span>
                            </td>
                            <td class="text-end text-dark small">{{ \App\Models\SlipGaji::rupiah($item->total_penghasilan) }}</td>
                            <td class="text-end text-dark small">{{ \App\Models\SlipGaji::rupiah($item->total_potongan) }}</td>
                            <td class="text-end fw-bold text-dark">{{ \App\Models\SlipGaji::rupiah($item->gaji_bersih) }}</td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- Tombol Detail (Icon mata, di dalamnya terdapat WhatsApp, Email, dan Cetak PDF) -->
                                    <a href="{{ route('slip-gaji.show', $item->id) }}" class="btn btn-outline-secondary" title="Lihat Rincian">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('slip-gaji.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus slip gaji ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <div class="mb-2">
                                    <i class="bi bi-inbox fs-2 text-secondary"></i>
                                </div>
                                <p class="small mb-2">Belum ada data slip gaji tersimpan.</p>
                                <a href="{{ route('slip-gaji.create') }}" class="btn btn-outline-secondary btn-sm">
                                    + Tambah Slip Gaji
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Filter pencarian tabel sederhana
    document.getElementById('searchTable')?.addEventListener('keyup', function() {
        const keyword = this.value.toLowerCase();
        const rows = document.querySelectorAll('#tableSlipGaji tbody tr');
        rows.forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(keyword) ? '' : 'none';
        });
    });
</script>
@endsection
