<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify ENUM to include 'ditolak'
        DB::statement("ALTER TABLE `marbots` MODIFY COLUMN `status` ENUM('diajukan', 'perbaikan', 'disetujui', 'ditolak') DEFAULT 'diajukan'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original ENUM (remove 'ditolak')
        DB::statement("ALTER TABLE `marbots` MODIFY COLUMN `status` ENUM('diajukan', 'perbaikan', 'disetujui') DEFAULT 'diajukan'");
    }
};
