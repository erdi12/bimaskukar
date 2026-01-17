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
        Schema::table('marbots', function (Blueprint $table) {
            $table->string('nomor_rekening')->nullable()->after('npwp');
            $table->string('file_buku_rekening')->nullable()->after('file_pernyataan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marbots', function (Blueprint $table) {
            $table->dropColumn(['nomor_rekening', 'file_buku_rekening']);
        });
    }
};
