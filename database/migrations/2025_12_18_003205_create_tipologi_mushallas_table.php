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
        Schema::create('tipologi_mushallas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tipologi');
            $table->timestamps();
        });

        // Insert default data
        DB::table('tipologi_mushallas')->insert([
            ['nama_tipologi' => 'Mushalla Perumahan', 'created_at' => now(), 'updated_at' => now()],
            ['nama_tipologi' => 'Mushalla di Tempat Publik', 'created_at' => now(), 'updated_at' => now()],
            ['nama_tipologi' => 'Mushalla Perkantoran', 'created_at' => now(), 'updated_at' => now()],
            ['nama_tipologi' => 'Mushalla Pendidikan', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipologi_mushallas');
    }
};
