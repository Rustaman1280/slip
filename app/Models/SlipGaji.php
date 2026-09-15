<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlipGaji extends Model
{
    protected $table = 'slip_gaji';

    public $timestamps = false;

    protected $fillable = [
        'no_slip',
        'tanggal',
        'periode',
        'nik',
        'nama_karyawan',
        'jabatan',
        'no_telepon',
        'gaji_pokok',
        'lembur',
        'total_penghasilan',
        'pinjaman',
        'total_potongan',
        'gaji_bersih',
        'keterangan',
        'created_by',
        'created_at',
    ];

    // fungsi pembantu buat ubah angka jadi format rupiah
    public static function rupiah($angka): string
    {
        return 'Rp '.number_format((float) $angka, 0, ',', '.');
    }

    // fungsi buat ubah angka jadi kata-kata terbilang rupiah
    public static function terbilang(float|int $nilai): string
    {
        $angka = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];
        $n = (int) floor($nilai);
        if ($n < 12) {
            return $angka[$n];
        }
        if ($n < 20) {
            return self::terbilang($n - 10).' Belas';
        }
        if ($n < 100) {
            return self::terbilang((int) floor($n / 10)).' Puluh '.self::terbilang($n % 10);
        }
        if ($n < 200) {
            return 'Seratus '.self::terbilang($n - 100);
        }
        if ($n < 1000) {
            return self::terbilang((int) floor($n / 100)).' Ratus '.self::terbilang($n % 100);
        }
        if ($n < 2000) {
            return 'Seribu '.self::terbilang($n - 1000);
        }
        if ($n < 1000000) {
            return self::terbilang((int) floor($n / 1000)).' Ribu '.self::terbilang($n % 1000);
        }
        if ($n < 1000000000) {
            return self::terbilang((int) floor($n / 1000000)).' Juta '.self::terbilang($n % 1000000);
        }
        if ($n < 1000000000000) {
            return self::terbilang((int) floor($n / 1000000000)).' Miliar '.self::terbilang($n % 1000000000);
        }

        return '';
    }

    // biar bisa langsung dipanggil $slip->terbilang
    public function getTerbilangAttribute(): string
    {
        $hasil = trim((string) preg_replace('/\s+/', ' ', self::terbilang($this->gaji_bersih)));

        return $hasil ? $hasil.' Rupiah' : '-';
    }

    // buat bikin link wa otomatis ada isi pesan rincian slipnya
    public function getWaUrlAttribute(): string
    {
        $telepon = preg_replace('/[^0-9]/', '', (string) $this->no_telepon);
        if (str_starts_with($telepon, '0')) {
            $telepon = '62'.substr($telepon, 1);
        }

        $periodeTeks = $this->periode ?: date('d F Y', strtotime($this->tanggal));

        $pesan = "Halo *{$this->nama_karyawan}*,\n\n"
            ."Berikut adalah rincian *SLIP GAJI KARYAWAN* Anda:\n"
            ."----------------------------------------\n"
            ."• No. Slip : {$this->no_slip}\n"
            ."• Periode : {$periodeTeks}\n"
            ."• NIK : {$this->nik}\n"
            ."• Jabatan : {$this->jabatan}\n"
            ."----------------------------------------\n"
            ."PENGHASILAN:\n"
            .'• Gaji Pokok : '.self::rupiah($this->gaji_pokok)."\n"
            .'• Uang Lembur : '.self::rupiah($this->lembur)."\n"
            .'• Total Penghasilan : '.self::rupiah($this->total_penghasilan)."\n"
            ."----------------------------------------\n"
            ."POTONGAN:\n"
            .'• Pinjaman Karyawan : '.self::rupiah($this->pinjaman)."\n"
            .'• Total Potongan : '.self::rupiah($this->total_potongan)."\n"
            ."----------------------------------------\n"
            .'*GAJI BERSIH DITERIMA : '.self::rupiah($this->gaji_bersih)."*\n"
            ."----------------------------------------\n"
            .'Terima kasih atas kerja keras Anda.';

        return 'https://wa.me/'.($telepon ?: '').'?text='.urlencode($pesan);
    }

    // buat bikin link buka gmail web langsung isi subjek dan pesan
    public function getEmailUrlAttribute(): string
    {
        $subjek = "Slip Gaji - {$this->no_slip} - {$this->nama_karyawan}";
        $periodeTeks = $this->periode ?: date('d F Y', strtotime($this->tanggal));

        $pesan = "Halo {$this->nama_karyawan},\n\n"
            ."Berikut adalah rincian SLIP GAJI KARYAWAN Anda:\n"
            ."========================================\n"
            ."No. Slip   : {$this->no_slip}\n"
            ."Periode    : {$periodeTeks}\n"
            ."NIK        : {$this->nik}\n"
            ."Jabatan    : {$this->jabatan}\n"
            ."========================================\n"
            ."PENGHASILAN:\n"
            .'- Gaji Pokok        : '.self::rupiah($this->gaji_pokok)."\n"
            .'- Uang Lembur       : '.self::rupiah($this->lembur)."\n"
            .'- Total Penghasilan : '.self::rupiah($this->total_penghasilan)."\n"
            ."========================================\n"
            ."POTONGAN:\n"
            .'- Pinjaman Karyawan : '.self::rupiah($this->pinjaman)."\n"
            .'- Total Potongan    : '.self::rupiah($this->total_potongan)."\n"
            ."========================================\n"
            .'GAJI BERSIH DITERIMA : '.self::rupiah($this->gaji_bersih)."\n"
            ."========================================\n\n"
            ."Terima kasih atas kontribusi Anda pada perusahaan.\n\n"
            ."Hormat kami,\n"
            .'Bagian Keuangan / HRD';

        return 'https://mail.google.com/mail/?view=cm&fs=1&su='.rawurlencode($subjek).'&body='.rawurlencode($pesan);
    }
}
