<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('content_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Уникальный идентификатор для каждого элемента контента
            $table->string('value'); // Значение (например, текст)
            $table->boolean('is_active')->default(true); // Включен/Отключен
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('content_settings');
    }
};
