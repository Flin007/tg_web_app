<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('telegram_users', function (Blueprint $table) {
            $table->index('user_id');
        });

        Schema::create('car_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('telegram_users');
            $table->foreignId('car_id')->constrained('cars');
            $table->enum('status', ['created', 'in_progress', 'completed'])->default('created');
            $table->json('data')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('telegram_users', function (Blueprint $table) {
            $table->dropIndex('telegram_users_user_id_index');
        });

        Schema::dropIfExists('car_requests');
    }
};
