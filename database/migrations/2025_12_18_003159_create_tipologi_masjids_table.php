<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tipologi_masjids', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tipologi');
            $table->timestamps();
        });

        // Insert default data
        DB::table('tipologi_masjids')->insert([
            ['nama_tipologi' => 'Masjid Agung', 'created_at' => now(), 'updated_at' => now()],
            ['nama_tipologi' => 'Masjid Besar', 'created_at' => now(), 'updated_at' => now()],
            ['nama_tipologi' => 'Masjid Jami', 'created_at' => now(), 'updated_at' => now()],
            ['nama_tipologi' => 'Masjid Bersejarah', 'created_at' => now(), 'updated_at' => now()],
            ['nama_tipologi' => 'Masjid di Tempat Publik', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipologi_masjids');
    }
};
