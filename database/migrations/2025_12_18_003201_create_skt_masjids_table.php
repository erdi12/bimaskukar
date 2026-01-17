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
        Schema::create('skt_masjids', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_id_masjid');
            $table->string('nama_masjid');
            $table->foreignId('kecamatan_id')->constrained('kecamatans')->onDelete('cascade');
            $table->foreignId('kelurahan_id')->constrained('kelurahans')->onDelete('cascade');
            $table->foreignId('tipologi_masjid_id')->constrained('tipologi_masjids')->onDelete('cascade');
            $table->text('alamat_masjid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skt_masjids');
    }
};
