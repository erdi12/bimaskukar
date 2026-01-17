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
        Schema::create('marbots', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->unique();
            $table->string('nama_lengkap');
            $table->text('alamat');
            $table->foreignId('kecamatan_id')->constrained('kecamatans');
            $table->foreignId('kelurahan_id')->constrained('kelurahans');
            $table->enum('tipe_rumah_ibadah', ['Masjid', 'Mushalla']);
            $table->unsignedBigInteger('rumah_ibadah_id'); // ID from skt_masjids or skt_mushallas

            // Files
            $table->string('file_ktp')->nullable();
            $table->string('file_kk')->nullable();
            $table->string('file_sk_marbot')->nullable();
            $table->string('file_permohonan')->nullable();
            $table->string('file_pernyataan')->nullable();

            // System
            $table->string('nomor_induk_marbot')->nullable()->unique();
            $table->enum('status', ['diajukan', 'perbaikan', 'disetujui'])->default('diajukan');
            $table->text('catatan')->nullable(); // For review notes

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marbots');
    }
};
