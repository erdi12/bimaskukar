<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('marbots', function (Blueprint $table) {
            $table->enum('status_umroh', ['kandidat', 'terverifikasi', 'berangkat'])->nullable()->after('verification_details');
            $table->year('tahun_umroh')->nullable()->after('status_umroh');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('marbots', function (Blueprint $table) {
            $table->dropColumn(['status_umroh', 'tahun_umroh']);
        });
    }
};
