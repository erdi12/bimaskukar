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
        $tables = ['skt_masjids', 'skt_mushallas', 'marbots', 'kecamatans', 'kelurahans'];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                // 1. Add nullable uuid
                Schema::table($tableName, function (Blueprint $table) {
                    $table->uuid('uuid')->after('id')->nullable();
                });

                // 2. Populate
                $records = \DB::table($tableName)->get();
                foreach ($records as $record) {
                    \DB::table($tableName)
                        ->where('id', $record->id)
                        ->update(['uuid' => (string) \Illuminate\Support\Str::uuid()]);
                }

                // 3. Make not null and unique
                Schema::table($tableName, function (Blueprint $table) {
                    $table->uuid('uuid')->nullable(false)->unique()->change();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['skt_masjids', 'skt_mushallas', 'marbots', 'kecamatans', 'kelurahans'];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('uuid');
                });
            }
        }
    }
};
