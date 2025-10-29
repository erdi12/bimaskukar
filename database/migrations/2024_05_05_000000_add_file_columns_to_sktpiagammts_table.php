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
        Schema::table('sktpiagammts', function (Blueprint $table) {
            $table->string('file_skt')->nullable()->after('mendaftar_ulang');
            $table->string('file_piagam')->nullable()->after('file_skt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sktpiagammts', function (Blueprint $table) {
            $table->dropColumn(['file_skt', 'file_piagam']);
        });
    }
};