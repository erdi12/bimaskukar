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
        Schema::table('marbot_insentifs', function (Blueprint $table) {
            // 1. Add column 'bulan' safely
            if (! Schema::hasColumn('marbot_insentifs', 'bulan')) {
                $table->tinyInteger('bulan')->after('tahun_anggaran')->comment('1-12');
            }

            // 2. Drop Foreign Key first to free up the index
            try {
                $table->dropForeign(['marbot_id']); // or 'marbot_insentifs_marbot_id_foreign'
            } catch (\Exception $e) {
            }

            // 3. Drop old constraint safely
            try {
                $table->dropUnique('marbot_insentifs_marbot_id_tahun_anggaran_unique');
            } catch (\Exception $e) {
                try {
                    $table->dropUnique(['marbot_id', 'tahun_anggaran']);
                } catch (\Exception $ex) {
                }
            }

            // 4. Add new unique constraint
            try {
                $table->unique(['marbot_id', 'tahun_anggaran', 'bulan'], 'unique_insentif_per_bulan');
            } catch (\Exception $e) {
            }

            // 5. Restore Foreign Key
            // We do this in a separate modify or just here.
            // Since we dropped it, we must add it back.
            try {
                $table->foreign('marbot_id')
                    ->references('id')
                    ->on('marbots')
                    ->onDelete('cascade');
            } catch (\Exception $e) {
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marbot_insentifs', function (Blueprint $table) {
            try {
                $table->dropForeign(['marbot_id']);
                $table->dropUnique('unique_insentif_per_bulan');
            } catch (\Exception $e) {
            }

            if (Schema::hasColumn('marbot_insentifs', 'bulan')) {
                $table->dropColumn('bulan');
            }

            try {
                $table->unique(['marbot_id', 'tahun_anggaran']);
                $table->foreign('marbot_id')->references('id')->on('marbots')->onDelete('cascade');
            } catch (\Exception $e) {
            }
        });
    }
};
