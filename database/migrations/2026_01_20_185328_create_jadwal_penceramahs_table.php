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
        Schema::create('jadwal_penceramahs', function (Blueprint $table) {
            $table->id();
            $table->string('tanggal_hijriah')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('ustadz_tarawih')->nullable();
            $table->string('ustadz_subuh')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_penceramahs');
    }
};
