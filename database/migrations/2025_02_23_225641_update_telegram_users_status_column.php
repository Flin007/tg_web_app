<?php

use App\Models\TelegramUser;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('telegram_users', function (Blueprint $table) {
            // Удаляем колонку is_active
            $table->dropColumn('is_active');

            // Добавляем колонку status с enum
            $table->enum('status', [
                TelegramUser::STATUS_MEMBER,
                TelegramUser::STATUS_LEFT,
                TelegramUser::STATUS_KICKED
            ])
                ->default('member')
                ->after('is_bot');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('telegram_users', function (Blueprint $table) {
            // Восстанавливаем is_active
            $table->boolean('is_active')->default(true);

            // Удаляем status
            $table->dropColumn('status');
        });
    }
};
