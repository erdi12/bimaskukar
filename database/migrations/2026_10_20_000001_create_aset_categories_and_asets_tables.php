<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aset_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('asets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aset_category_id')->constrained('aset_categories')->restrictOnDelete();
            $table->string('asset_code')->unique();
            $table->string('name');
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->date('acquisition_date')->nullable();
            $table->string('acquisition_source')->nullable();
            $table->decimal('acquisition_value', 15, 2)->nullable();
            $table->string('condition')->default('baik');
            $table->string('status')->default('digunakan');
            $table->string('location')->nullable();
            $table->string('responsible_person')->nullable();
            $table->json('specifications')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asets');
        Schema::dropIfExists('aset_categories');
    }
};
