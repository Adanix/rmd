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
            $table->foreignId('jamaah_id')->constrained('jamaahs')->onDelete('cascade');
            $table->string('nama');
            $table->string('alamat');
            $table->date('tanggal_masehi');
            $table->string('tanggal_hijriyah')->nullable();
            $table->json('keterangan')->nullable();
            $table->timestamps();

            // Unique constraint untuk mencegah double registrasi
            $table->unique(['jamaah_id', 'tanggal_masehi']);
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
