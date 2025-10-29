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
        Schema::create('skt_rumah_ibadahs', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_statistik');
            $table->string('nama_rumah_ibadah');
            $table->string('alamat');
            $table->foreignId('kecamatan_id')
                ->constrained('kecamatans')
                ->onDelete('cascade');
            $table->foreignId('kelurahan_id')
                ->constrained('kelurahans')
                ->onDelete('cascade');
            $table->foreignId('jenis_rumah_ibadah_id')
                ->constrained('jenis_rumah_ibadahs')
                ->onDelete('cascade');
            $table->foreignId('tipologi_rumah_ibadah_id')
                ->constrained('tipologi_rumah_ibadahs')
                ->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skt_rumah_ibadahs');
    }
};
