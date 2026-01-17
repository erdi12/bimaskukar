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
        // 1. Add nullable uuid column first
        Schema::table('sktpiagammts', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->nullable();
        });

        // 2. Populate UUID for existing records
        $records = \DB::table('sktpiagammts')->get();
        foreach ($records as $record) {
            \DB::table('sktpiagammts')
                ->where('id', $record->id)
                ->update(['uuid' => (string) \Illuminate\Support\Str::uuid()]);
        }

        // 3. Change column to not null and unique
        Schema::table('sktpiagammts', function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sktpiagammts', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
