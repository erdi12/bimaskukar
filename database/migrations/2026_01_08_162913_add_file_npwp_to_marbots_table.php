<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('marbots', function (Blueprint $table) {
            $table->string('file_npwp')->nullable()->after('npwp');
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
            $table->dropColumn('file_npwp');
        });
    }
};
