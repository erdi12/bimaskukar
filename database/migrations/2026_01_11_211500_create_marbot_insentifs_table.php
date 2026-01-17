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
        Schema::create('marbot_insentifs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marbot_id')->constrained('marbots')->onDelete('cascade');
            $table->year('tahun_anggaran');
            $table->decimal('nominal', 15, 2);
            $table->date('tanggal_terima');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Add unique constraint to prevent duplicate incentives for the same year
            $table->unique(['marbot_id', 'tahun_anggaran']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marbot_insentifs');
    }
};
