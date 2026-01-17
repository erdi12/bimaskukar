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
        Schema::dropIfExists('skt_rumah_ibadahs');
        Schema::dropIfExists('tipologi_rumah_ibadahs');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-creating the tables if needed. 
        // Note: The original schemas should be restored if rolling back.
        // For now we leave it empty or you can copy the schema from the original files if strict rollback is needed.
    }
};
