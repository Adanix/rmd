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
        Schema::create('ramadhan_settings', function (Blueprint $table) {
            $table->id();
            $table->date('start_date'); // tanggal Masehi hari 1
            $table->integer('days'); // 29 atau 30
            $table->integer('quota_per_day')->default(10);
            $table->json('special_quotas')->nullable(); // {"2025-03-10":20, ...}
            $table->json('holidays')->nullable(); // ["friday","2025-03-15",...]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ramadhan_settings');
    }
};
