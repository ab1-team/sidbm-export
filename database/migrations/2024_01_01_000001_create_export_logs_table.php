<?php

// database/migrations/2024_01_01_000001_create_export_logs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel ini menyimpan metadata setiap file yang sudah dieksport.
     *
     * Kenapa perlu tabel ini?
     * Karena file ada di EnStorage, kita perlu tahu:
     * - File apa saja yang sudah dieksport
     * - Kapan dieksport
     * - URL file di EnStorage (untuk link download di UI)
     * - Status export (sukses/gagal)
     */
    public function up(): void
    {
        Schema::create('export_logs', function (Blueprint $table) {
            $table->id();

            // ID kecamatan — referensi ke tabel kecamatan di DB SIDBM
            $table->unsignedInteger('kecamatan_id');

            // Jenis data: 'saldo' atau 'transaksi'
            $table->enum('jenis', ['saldo', 'transaksi']);

            $table->unsignedSmallInteger('tahun');

            // Hanya diisi untuk transaksi (1-12), NULL untuk saldo
            $table->unsignedTinyInteger('bulan')->nullable();

            // Nama file di EnStorage, misal: saldo_2023.json
            $table->string('filename');

            // URL lengkap file di EnStorage untuk akses langsung
            $table->string('file_url')->nullable();

            // Ukuran file dalam bytes
            $table->unsignedBigInteger('file_size')->nullable();

            // Jumlah record yang dieksport
            $table->unsignedInteger('record_count')->nullable();

            // Status: pending, success, failed
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');

            // Pesan error jika status = failed
            $table->text('error_message')->nullable();

            // Siapa yang trigger export (null jika via scheduler)
            $table->string('triggered_by')->nullable();

            $table->timestamps();

            // Index untuk query yang sering dipakai
            $table->index(['kecamatan_id', 'jenis', 'tahun']);
            $table->index(['kecamatan_id', 'jenis', 'tahun', 'bulan']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('export_logs');
    }
};
