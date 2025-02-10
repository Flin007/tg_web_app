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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_available')->default(true);
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('year'); //год
            $table->string('engine'); //Для двигателя (например, "1.5 116 л.с.")
            $table->string('transmission'); //Для трансмиссии (например, "Вариатор CVT18")
            $table->string('drive'); //Для привода (например, "Передний привод"):
            $table->string('trim'); //Для комплектации (например, "1.5 CVT Classic"):
            $table->string('interior'); //Для интерьера (например, "Чёрная кожа")
            $table->string('vin')->unique(); //Для VIN (например, "LVVDDXYZYZ***01")
            $table->decimal('price', 10, 2); //Для актуальной стоимости, decimal для точности
            $table->decimal('old_price', 10, 2)->nullable(); //Для прошлой стоимости (например, для отображения скидки
            $table->foreignId('city_id')->constrained('car_cities')->onDelete('restrict');
            $table->foreignId('brand_id')->constrained('car_brands')->onDelete('restrict');
            $table->foreignId('model_id')->constrained('car_models')->onDelete('restrict');
            $table->index('brand_id');
            $table->index('model_id');
            $table->index('city_id');
            $table->index('year');
            $table->index('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
