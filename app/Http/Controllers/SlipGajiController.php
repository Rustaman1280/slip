<?php

namespace App\Http\Controllers;

use App\Models\SlipGaji;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SlipGajiController extends Controller
{
    /**
     * Halaman Utama: Menampilkan Riwayat Slip Gaji Karyawan beserta ringkasan statistik
     */
    public function index(): View
    {
        $daftarSlip = SlipGaji::orderBy('id', 'desc')->get();

        // Ringkasan statistik untuk kartu dashboard
        $totalSlip = $daftarSlip->count();
        $totalPengeluaran = $daftarSlip->sum('gaji_bersih');
        $rataRataGaji = $totalSlip > 0 ? ($totalPengeluaran / $totalSlip) : 0;

        return view('gaji.index', compact('daftarSlip', 'totalSlip', 'totalPengeluaran', 'rataRataGaji'));
    }

    /**
     * Halaman Tambah: Menampilkan form input slip gaji dengan captcha perkalian sederhana
     */
    public function create(): View
    {
        // Buat soal captcha perkalian mudah (angka 2 s/d 9 dikali 2 s/d 5)
        $angka1 = rand(2, 9);
        $angka2 = rand(2, 5);
        $captchaSoal = "{$angka1} × {$angka2}";
        $captchaJawaban = $angka1 * $angka2;

        session(['captcha_jawaban' => $captchaJawaban]);

        return view('gaji.create', compact('captchaSoal'));
    }

    /**
     * Memproses perhitungan gaji dan menyimpan ke database
     *
     * Rumus sesuai soal UKK:
     * a. Total penghasilan = Gaji Pokok + Lembur
     * b. Total Potongan = Pinjaman Karyawan
     * c. Gaji Bersih = Total penghasilan - Total Potongan
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi input
        $request->validate([
            'nik' => 'required|string|max:30',
            'nama_karyawan' => 'required|string|max:100',
            'jabatan' => 'required|string|max:50',
            'periode_mulai' => 'nullable|date',
            'periode_selesai' => 'nullable|date',
            'periode' => 'nullable|string|max:100',
            'no_telepon' => 'nullable|string|max:20',
            'gaji_pokok' => 'required|numeric|min:0',
            'lembur' => 'nullable|numeric|min:0',
            'pinjaman' => 'nullable|numeric|min:0',
            'captcha' => 'required|numeric',
        ], [
            'nik.required' => 'NIK karyawan wajib diisi.',
            'nama_karyawan.required' => 'Nama karyawan wajib diisi.',
            'jabatan.required' => 'Jabatan wajib diisi.',
            'gaji_pokok.required' => 'Gaji pokok wajib diisi.',
            'captcha.required' => 'Jawaban captcha wajib diisi.',
            'captcha.numeric' => 'Jawaban captcha harus berupa angka.',
        ]);

        // 2. Verifikasi Captcha Perkalian Sederhana
        if ((int) $request->input('captcha') !== (int) session('captcha_jawaban')) {
            return back()
                ->withInput()
                ->with('error', 'Jawaban perkalian Captcha salah! Silakan coba lagi.');
        }

        // 3. Format Periode dari kalender Dari s/d Sampai
        if ($request->filled('periode_mulai') && $request->filled('periode_selesai')) {
            $namaBulan = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
            ];

            $tglMulai = strtotime($request->input('periode_mulai'));
            $tglSelesai = strtotime($request->input('periode_selesai'));

            $mulaiStr = date('j', $tglMulai).' '.$namaBulan[(int) date('n', $tglMulai)].' '.date('Y', $tglMulai);
            $selesaiStr = date('j', $tglSelesai).' '.$namaBulan[(int) date('n', $tglSelesai)].' '.date('Y', $tglSelesai);

            $periode = "{$mulaiStr} - {$selesaiStr}";
            $tanggal = $request->input('periode_selesai');
        } else {
            $periode = $request->input('periode', date('d F Y'));
            $tanggal = date('Y-m-d');
        }

        // 4. Ambil nilai angka (default 0 bila kosong)
        $gajiPokok = (float) $request->input('gaji_pokok', 0);
        $lembur = (float) $request->input('lembur', 0);
        $pinjaman = (float) $request->input('pinjaman', 0);

        // 5. Perhitungan gaji sesuai ketentuan Soal UKK
        $totalPenghasilan = $gajiPokok + $lembur;
        $totalPotongan = $pinjaman;
        $gajiBersih = $totalPenghasilan - $totalPotongan;

        // 6. Generate Nomor Slip Otomatis: SLIP-YYYYMM-00X
        $tahunBulan = date('Ym');
        $jumlahDataBulanIni = SlipGaji::where('no_slip', 'LIKE', "SLIP-{$tahunBulan}-%")->count();
        $urutan = str_pad($jumlahDataBulanIni + 1, 3, '0', STR_PAD_LEFT);
        $noSlip = "SLIP-{$tahunBulan}-{$urutan}";

        // 7. Simpan ke database
        $slip = SlipGaji::create([
            'no_slip' => $noSlip,
            'tanggal' => $tanggal,
            'periode' => $periode,
            'nik' => $request->input('nik'),
            'nama_karyawan' => $request->input('nama_karyawan'),
            'jabatan' => $request->input('jabatan'),
            'no_telepon' => $request->input('no_telepon'),
            'gaji_pokok' => $gajiPokok,
            'lembur' => $lembur,
            'total_penghasilan' => $totalPenghasilan,
            'pinjaman' => $pinjaman,
            'total_potongan' => $totalPotongan,
            'gaji_bersih' => $gajiBersih,
            'keterangan' => $request->input('keterangan'),
            'created_by' => Auth::id() ?? 1,
            'created_at' => now(),
        ]);

        return redirect()->route('slip-gaji.index')
            ->with('success', "Slip Gaji {$slip->no_slip} untuk {$slip->nama_karyawan} berhasil dihitung dan ditambahkan!");
    }

    /**
     * Menampilkan detail rincian resmi slip gaji (lengkap dengan tombol WhatsApp, Email, dan Cetak PDF)
     */
    public function show(int $id): View
    {
        $slip = SlipGaji::findOrFail($id);

        return view('gaji.show', compact('slip'));
    }

    /**
     * Tampilan cetak resmi slip gaji (siap diprint ke printer atau PDF)
     */
    public function cetak(int $id): View
    {
        $slip = SlipGaji::findOrFail($id);

        return view('gaji.cetak', compact('slip'));
    }

    /**
     * Menghapus data slip gaji
     */
    public function destroy(int $id): RedirectResponse
    {
        $slip = SlipGaji::findOrFail($id);
        $slip->delete();

        return redirect()->route('slip-gaji.index')
            ->with('success', 'Data slip gaji berhasil dihapus.');
    }
}
