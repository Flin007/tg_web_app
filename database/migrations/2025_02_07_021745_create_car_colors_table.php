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
        Schema::create('car_colors', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Название цвета, например "Белый"
            $table->string('hex_code')->nullable(); // HEX-код цвета, например "#FFFFFF"
            $table->boolean('is_active')->default(true); // Для управления доступностью цвета
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_colors');
    }
};
