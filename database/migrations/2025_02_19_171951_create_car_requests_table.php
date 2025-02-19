<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('telegram_users');
            $table->foreignId('car_id')->constrained('cars');
            $table->enum('status', ['created', 'in_progress', 'completed'])->default('created');
            $table->json('data')->nullable(); // Для хранения всех данных формы в формате JSON
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_requests');
    }
};
