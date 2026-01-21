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
        Schema::create('takjils', function (Blueprint $table) {
            $table->id();

            $table->foreignId('jamaah_id')
                ->constrained('jamaahs')
                ->onDelete('cascade');

            $table->foreignId('day_setting_id')
                ->constrained('day_settings')
                ->onDelete('cascade');

            $table->string('tanggal_hijriyah')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['jamaah_id', 'day_setting_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('takjils');
    }
};
