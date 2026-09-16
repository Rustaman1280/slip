<?php

namespace Tests\Feature;

use App\Models\SlipGaji;
use App\Models\User;
use Tests\TestCase;

class SlipGajiTest extends TestCase
{
    // tes halaman login bisa dibuka apa ngga, sekalian mastiin gak ada navbar
    public function test_login_page_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Masuk');
        $response->assertSee('Silakan masukkan username dan password');
        $response->assertDontSee('UKK PENGGAJIAN'); // mastiin navbar atas gak muncul pas di login
    }

    // tes login pake username sama password yang bener
    public function test_user_can_login_with_valid_credentials(): void
    {
        $response = $this->post('/login', [
            'username' => 'admin',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/slip-gaji');
        $this->assertAuthenticated();
    }

    // tes login kalo passwordnya salah, harusnya dapet error
    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $response = $this->post('/login', [
            'username' => 'admin',
            'password' => 'passwordsalah',
        ]);

        $response->assertSessionHas('error');
        $this->assertGuest();
    }

    // tes biar orang yang belum login gak bisa sembarangan masuk ke halaman slip gaji
    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get('/slip-gaji');

        $response->assertRedirect('/login');
        $response->assertSessionHas('error');
    }

    // tes buka halaman riwayat slip gaji setelah berhasil login
    public function test_slip_gaji_index_page_can_be_rendered_when_authenticated(): void
    {
        $admin = User::first();
        $this->actingAs($admin);

        $response = $this->get('/slip-gaji');

        $response->assertStatus(200);
        $response->assertSee('Riwayat Slip Gaji Karyawan');
        $response->assertSee('Tambah Slip Gaji');
        $response->assertSee('UKK PENGGAJIAN'); // navbar atas harusnya muncul pas udah login
    }

    // tes buka form tambah slip gaji setelah login
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

    // tes hitung-hitungan rumus gaji bersih sama nyimpen ke database
    public function test_perhitungan_dan_penyimpanan_slip_gaji(): void
    {
        $admin = User::first();
        $this->actingAs($admin);

        // set jawaban captcha di session biar lolos validasi
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

        // cek apakah datanya beneran kesimpen di database
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

        // pastiin link wa sama link email gmail nya kebentuk otomatis
        $this->assertStringContainsString('https://wa.me/628123456789', $slip->wa_url);
        $this->assertStringContainsString('https://mail.google.com/mail/?view=cm', $slip->email_url);

        // hapus data tes biar gak nyampah di database
        $slip->delete();
    }

    // tes kalo captchanya salah, harusnya gagal simpen
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
            'captcha' => 99, // jawaban sengaja disalahin
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('slip_gaji', [
            'nik' => 'TEST-002',
        ]);
    }

    // tes buat nampilin halaman cetak slip gaji
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

    // tes buka halaman edit slip gaji setelah login
    public function test_slip_gaji_edit_page_can_be_rendered_when_authenticated(): void
    {
        $admin = User::first();
        $this->actingAs($admin);

        $slip = SlipGaji::first();
        if (! $slip) {
            $slip = SlipGaji::create([
                'no_slip' => 'SLIP-TEST-001',
                'tanggal' => date('Y-m-d'),
                'periode' => '25 November 2025 - 25 Desember 2025',
                'nik' => 'TEST-EDIT-01',
                'nama_karyawan' => 'Testing Edit User',
                'jabatan' => 'Staff IT',
                'gaji_pokok' => 4000000,
                'lembur' => 500000,
                'total_penghasilan' => 4500000,
                'pinjaman' => 200000,
                'total_potongan' => 200000,
                'gaji_bersih' => 4300000,
                'created_by' => $admin->id,
            ]);
        }

        $response = $this->get(route('slip-gaji.edit', $slip->id));

        $response->assertStatus(200);
        $response->assertSee('EDIT SLIP GAJI KARYAWAN');
        $response->assertSee($slip->no_slip);
        $response->assertSee($slip->nama_karyawan);
        $response->assertSee('Simpan Perubahan');
    }

    // tes proses update data slip gaji
    public function test_slip_gaji_can_be_updated(): void
    {
        $admin = User::first();
        $this->actingAs($admin);

        $slip = SlipGaji::create([
            'no_slip' => 'SLIP-TEST-UPDATE',
            'tanggal' => '2026-01-25',
            'periode' => '01 Januari 2026 - 25 Januari 2026',
            'nik' => 'TEST-UPDATE-01',
            'nama_karyawan' => 'Karyawan Lama',
            'jabatan' => 'Junior Staff',
            'gaji_pokok' => 3000000,
            'lembur' => 0,
            'total_penghasilan' => 3000000,
            'pinjaman' => 0,
            'total_potongan' => 0,
            'gaji_bersih' => 3000000,
            'created_by' => $admin->id,
        ]);

        $response = $this->put(route('slip-gaji.update', $slip->id), [
            'nik' => 'TEST-UPDATE-01-NEW',
            'nama_karyawan' => 'Karyawan Diperbarui',
            'jabatan' => 'Senior Developer',
            'periode_mulai' => '2026-01-01',
            'periode_selesai' => '2026-01-31',
            'no_telepon' => '081299998888',
            'gaji_pokok' => 6000000,
            'lembur' => 1500000,
            'pinjaman' => 500000,
            'keterangan' => 'Gaji lembur proyek selesai',
        ]);

        $response->assertRedirect(route('slip-gaji.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('slip_gaji', [
            'id' => $slip->id,
            'nik' => 'TEST-UPDATE-01-NEW',
            'nama_karyawan' => 'Karyawan Diperbarui',
            'jabatan' => 'Senior Developer',
            'gaji_pokok' => 6000000,
            'lembur' => 1500000,
            'total_penghasilan' => 7500000,
            'pinjaman' => 500000,
            'total_potongan' => 500000,
            'gaji_bersih' => 7000000,
            'keterangan' => 'Gaji lembur proyek selesai',
        ]);

        $slip->delete();
    }
}
