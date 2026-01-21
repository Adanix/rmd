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
        Schema::create('takjil_minumans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('takjil_id')
                ->constrained('takjils')
                ->onDelete('cascade');

            $table->foreignId('minuman_id')
                ->constrained('minumans')
                ->onDelete('cascade');

            $table->unsignedInteger('jumlah')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('takjil_minumans');
    }
};
