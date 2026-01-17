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
        Schema::table('skt_masjids', function (Blueprint $table) {
            $table->string('file_skt')->nullable()->after('alamat_masjid');
        });

        Schema::table('skt_mushallas', function (Blueprint $table) {
            $table->string('file_skt')->nullable()->after('alamat_mushalla');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skt_masjids', function (Blueprint $table) {
            $table->dropColumn('file_skt');
        });

        Schema::table('skt_mushallas', function (Blueprint $table) {
            $table->dropColumn('file_skt');
        });
    }
};
