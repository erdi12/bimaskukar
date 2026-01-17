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
            if (! Schema::hasColumn('skt_masjids', 'file_barcode_masjid')) {
                $table->string('file_barcode_masjid')->nullable()->after('alamat_masjid');
            }
        });

        Schema::table('skt_mushallas', function (Blueprint $table) {
            if (! Schema::hasColumn('skt_mushallas', 'file_barcode_mushalla')) {
                $table->string('file_barcode_mushalla')->nullable()->after('alamat_mushalla');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skt_masjids', function (Blueprint $table) {
            if (Schema::hasColumn('skt_masjids', 'file_barcode_masjid')) {
                $table->dropColumn('file_barcode_masjid');
            }
        });

        Schema::table('skt_mushallas', function (Blueprint $table) {
            if (Schema::hasColumn('skt_mushallas', 'file_barcode_mushalla')) {
                $table->dropColumn('file_barcode_mushalla');
            }
        });
    }
};
