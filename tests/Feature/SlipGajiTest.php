<?php

namespace Tests\Feature;

use App\Models\SlipGaji;
use App\Models\User;
use Tests\TestCase;

class SlipGajiTest extends TestCase
{
    /**
     * Uji halaman login dapat diakses dengan status 200 dan tanpa navbar
     */
    public function test_login_page_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Masuk');
        $response->assertSee('Silakan masukkan username dan password');
        $response->assertDontSee('UKK PENGGAJIAN'); // Pastikan tidak ada top bar
    }

    /**
     * Uji proses login berhasil dengan kredensial yang benar
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        $response = $this->post('/login', [
            'username' => 'admin',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/slip-gaji');
        $this->assertAuthenticated();
    }

    /**
     * Uji proses login gagal jika kata sandi salah
     */
    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $response = $this->post('/login', [
            'username' => 'admin',
            'password' => 'passwordsalah',
        ]);

        $response->assertSessionHas('error');
        $this->assertGuest();
    }

    /**
     * Uji middleware cek.login mencegah akses tamu (guest) ke halaman slip gaji
     */
    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get('/slip-gaji');

        $response->assertRedirect('/login');
        $response->assertSessionHas('error');
    }

    /**
     * Uji halaman riwayat slip gaji dapat diakses setelah login
     */
    public function test_slip_gaji_index_page_can_be_rendered_when_authenticated(): void
    {
        $admin = User::first();
        $this->actingAs($admin);

        $response = $this->get('/slip-gaji');

        $response->assertStatus(200);
        $response->assertSee('Riwayat Slip Gaji Karyawan');
        $response->assertSee('Tambah Slip Gaji');
        $response->assertSee('UKK PENGGAJIAN'); // Top bar tampil setelah login
    }

    /**
     * Uji halaman form tambah slip gaji dapat diakses setelah login
     */
    public function test_slip_gaji_create_page_can_be_rendered_when_authenticated(): void
    {
        $admin = User::first();
        $this->actingAs($admin);

        $response = $this->get('/slip-gaji/tambah');

        $response->assertStatus(200);
        $response->assertSee('SLIP GAJI KARYAWAN');
        $response->assertSee('PERIODE GAJI');
        $response->assertSee('Penghasilan');
        $response->assertSee('Potongan');
        $response->assertSee('Gaji Bersih');
    }

    /**
     * Uji perhitungan gaji bersih dan penyimpanan ke database
     *
     * Ketentuan Soal UKK:
     * a. Total penghasilan = Gaji Pokok + Lembur
     * b. Total Potongan = Pinjaman Karyawan
     * c. Gaji Bersih = Total penghasilan - Total Potongan
     */
    public function test_perhitungan_dan_penyimpanan_slip_gaji(): void
    {
        $admin = User::first();
        $this->actingAs($admin);

        // Set session captcha perkalian
        $captchaJawaban = 12;
        session(['captcha_jawaban' => $captchaJawaban]);

        $gajiPokok = 5000000;
        $lembur = 1000000;
        $pinjaman = 500000;

        $response = $this->withSession(['captcha_jawaban' => $captchaJawaban])->post('/slip-gaji', [
            'nik' => 'TEST-001',
            'nama_karyawan' => 'Testing Programmer',
            'jabatan' => 'Junior Web Programmer',
            'periode' => '25 November 2025 - 25 Desember 2025',
            'no_telepon' => '08123456789',
            'gaji_pokok' => $gajiPokok,
            'lembur' => $lembur,
            'pinjaman' => $pinjaman,
            'captcha' => $captchaJawaban,
        ]);

        // Verifikasi hasil perhitungan di database
        $this->assertDatabaseHas('slip_gaji', [
            'nik' => 'TEST-001',
            'periode' => '25 November 2025 - 25 Desember 2025',
            'gaji_pokok' => $gajiPokok,
            'lembur' => $lembur,
            'total_penghasilan' => 6000000,
            'pinjaman' => $pinjaman,
            'total_potongan' => 500000,
            'gaji_bersih' => 5500000,
        ]);

        $slip = SlipGaji::where('nik', 'TEST-001')->first();
        $this->assertNotNull($slip);
        $response->assertRedirect(route('slip-gaji.index'));

        // Pastikan accessor wa_url dan email_url bekerja (membuka WhatsApp dan Gmail)
        $this->assertStringContainsString('https://wa.me/628123456789', $slip->wa_url);
        $this->assertStringContainsString('https://mail.google.com/mail/?view=cm', $slip->email_url);

        // Bersihkan data tes
        $slip->delete();
    }

    /**
     * Uji validasi gagal jika captcha tidak sesuai
     */
    public function test_gagal_simpan_jika_captcha_salah(): void
    {
        $admin = User::first();
        $this->actingAs($admin);

        $response = $this->withSession(['captcha_jawaban' => 20])->post('/slip-gaji', [
            'nik' => 'TEST-002',
            'nama_karyawan' => 'Testing Captcha Salah',
            'jabatan' => 'Staff',
            'periode' => 'Bulan Ini',
            'gaji_pokok' => 3000000,
            'lembur' => 0,
            'pinjaman' => 0,
            'captcha' => 99, // Jawaban salah
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('slip_gaji', [
            'nik' => 'TEST-002',
        ]);
    }

    /**
     * Uji halaman cetak slip gaji dapat dirender
     */
    public function test_halaman_cetak_slip_gaji(): void
    {
        $admin = User::first();
        $this->actingAs($admin);

        $slip = SlipGaji::first();

        if ($slip) {
            $response = $this->get(route('slip-gaji.cetak', $slip->id));
            $response->assertStatus(200);
            $response->assertSee($slip->no_slip);
            $response->assertSee($slip->nama_karyawan);
        }
    }
}
