<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // sktpiagammts migration
    public function up(): void
    {
        Schema::create('sktpiagammts', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_statistik')->unique(); // unik + index
            $table->string('nama_majelis');
            $table->text('alamat');

            $table->foreignId('kecamatan_id')
                ->constrained('kecamatans')
                ->onDelete('cascade');

            $table->foreignId('kelurahan_id')
                ->constrained('kelurahans')
                ->onDelete('cascade');

            $table->date('tanggal_berdiri')->index();
            $table->enum('status', ['aktif', 'nonaktif', 'belum_update'])->index();
            $table->string('ketua');
            $table->string('no_hp');
            $table->string('mendaftar');
            $table->string('mendaftar_ulang');
            $table->timestamps();
            $table->softDeletes();
            $table->index('deleted_at'); // optimasi soft delete
        });

        // Composite index utk query gabungan
        Schema::table('sktpiagammts', function (Blueprint $table) {
            $table->index(['kecamatan_id', 'kelurahan_id']);
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sktpiagammts');
    }
};
