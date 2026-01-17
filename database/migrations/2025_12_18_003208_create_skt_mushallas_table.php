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
        Schema::create('skt_mushallas', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_id_mushalla');
            $table->string('nama_mushalla');
            $table->foreignId('kecamatan_id')->constrained('kecamatans')->onDelete('cascade');
            $table->foreignId('kelurahan_id')->constrained('kelurahans')->onDelete('cascade');
            $table->foreignId('tipologi_mushalla_id')->constrained('tipologi_mushallas')->onDelete('cascade');
            $table->text('alamat_mushalla');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skt_mushallas');
    }
};
