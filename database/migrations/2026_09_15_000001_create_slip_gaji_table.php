<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('slip_gaji', function (Blueprint $table) {
            $table->id();
            $table->string('no_slip', 50);
            $table->date('tanggal');
            $table->string('periode', 100)->nullable();
            $table->string('nik', 30);
            $table->string('nama_karyawan', 100);
            $table->string('jabatan', 50);
            $table->string('no_telepon', 20)->nullable();
            $table->decimal('gaji_pokok', 15, 2);
            $table->decimal('lembur', 15, 2)->default(0);
            $table->decimal('total_penghasilan', 15, 2);
            $table->decimal('pinjaman', 15, 2)->default(0);
            $table->decimal('total_potongan', 15, 2)->default(0);
            $table->decimal('gaji_bersih', 15, 2);
            $table->text('keterangan')->nullable();
            $table->string('created_by', 50)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slip_gaji');
    }
};
