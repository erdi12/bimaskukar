<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_berkas', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('kode_tiket', 12)->unique();
            $table->foreignId('seleksi_berkas_id')->constrained('seleksi_berkas')->onDelete('cascade');
            $table->string('nama_pengaju');
            $table->string('no_hp');
            $table->json('data_isian')->nullable(); // Data field dinamis dari user
            $table->json('berkas_files')->nullable(); // Path file-file yang diupload
            $table->enum('status', ['menunggu', 'diproses', 'diterima', 'ditolak'])->default('menunggu');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_berkas');
    }
};
