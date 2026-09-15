<?php

namespace Database\Seeders;

use App\Models\SlipGaji;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat Akun Admin Pengujian UKK
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'nama_lengkap' => 'Administrator',
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // 2. Buat Data Awal Slip Gaji Karyawan
        $dummySlip = [
            [
                'no_slip' => 'SLIP-202609-001',
                'tanggal' => '2025-12-25',
                'periode' => '25 November 2025 - 25 Desember 2025',
                'nik' => '320101234567',
                'nama_karyawan' => 'Budi Santoso',
                'jabatan' => 'Web Developer',
                'no_telepon' => '081234567890',
                'gaji_pokok' => 5000000,
                'lembur' => 1000000,
                'total_penghasilan' => 6000000,
                'pinjaman' => 500000,
                'total_potongan' => 500000,
                'gaji_bersih' => 5500000,
                'created_by' => 'admin',
            ],
            [
                'no_slip' => 'SLIP-202609-002',
                'tanggal' => '2025-12-25',
                'periode' => '25 November 2025 - 25 Desember 2025',
                'nik' => '320109876543',
                'nama_karyawan' => 'Siti Aminah',
                'jabatan' => 'UI/UX Designer',
                'no_telepon' => '085712345678',
                'gaji_pokok' => 4800000,
                'lembur' => 500000,
                'total_penghasilan' => 5300000,
                'pinjaman' => 200000,
                'total_potongan' => 200000,
                'gaji_bersih' => 5100000,
                'created_by' => 'admin',
            ],
            [
                'no_slip' => 'SLIP-202609-003',
                'tanggal' => '2026-02-28',
                'periode' => '01 Februari 2026 - 28 Februari 2026',
                'nik' => '320109990005',
                'nama_karyawan' => 'Ahmad Fauzi',
                'jabatan' => 'Staff IT',
                'no_telepon' => '081987654321',
                'gaji_pokok' => 4200000,
                'lembur' => 350000,
                'total_penghasilan' => 4550000,
                'pinjaman' => 150000,
                'total_potongan' => 150000,
                'gaji_bersih' => 4400000,
                'created_by' => 'admin',
            ],
        ];

        foreach ($dummySlip as $slip) {
            SlipGaji::updateOrCreate(
                ['no_slip' => $slip['no_slip']],
                $slip
            );
        }
    }
}
