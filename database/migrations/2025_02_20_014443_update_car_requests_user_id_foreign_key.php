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
        Schema::table('car_requests', function (Blueprint $table) {
            $table->dropForeign('car_requests_user_id_foreign');
            $table->foreign('user_id')->references('user_id')->on('telegram_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('car_requests', function (Blueprint $table) {
            $table->dropForeign('car_requests_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('telegram_users')->onDelete('cascade');
        });
    }
};
