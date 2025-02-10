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
        Schema::create('car_color', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained('cars')->onDelete('cascade');
            $table->foreignId('color_id')->constrained('car_colors')->onDelete('restrict');
            $table->integer('sort_order')->default(0); // Для указания порядка цветов (например, основной и дополнительный)
            $table->unique(['car_id', 'color_id']); // Чтобы одна машина не могла иметь один и тот же цвет дважды
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_color');
    }
};
