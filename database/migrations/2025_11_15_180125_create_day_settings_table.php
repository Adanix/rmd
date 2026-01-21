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
        Schema::create('day_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ramadhan_setting_id')
                ->constrained('ramadhan_settings')
                ->onDelete('cascade');

            $table->date('date'); // contoh: 2025-03-01
            $table->unsignedInteger('quota'); // contoh: 16 jamaah

            $table->unsignedInteger('total_makanan_planned')->nullable();
            $table->unsignedInteger('total_minuman_planned')->nullable();

            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('day_settings');
    }
};
