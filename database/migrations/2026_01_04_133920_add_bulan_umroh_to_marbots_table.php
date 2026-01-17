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
            $table->tinyInteger('bulan_umroh')->nullable()->after('tahun_umroh');
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
            $table->dropColumn('bulan_umroh');
        });
    }
};
