<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // kelurahans migration
    
    public function up(): void
    {
        Schema::create('kelurahans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelurahan')->index();

            $table->foreignId('kecamatan_id')
                  ->constrained('kecamatans')
                  ->onDelete('cascade')
                  ->index();

            $table->timestamps();
            $table->softDeletes();
            $table->index('deleted_at');
        });

        // Composite index utk query gabungan
        Schema::table('kelurahans', function (Blueprint $table) {
            $table->index(['id', 'kecamatan_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelurahans');
    }
};
