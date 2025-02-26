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
        Schema::table('telegram_users', function (Blueprint $table) {
            $table
                ->boolean('is_blocked')
                ->default(false)
                ->after('is_active')
                ->comment('Означает, что пользователь был заблокирован в системе');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('telegram_users', function (Blueprint $table) {
            $table->dropColumn('is_blocked');
        });
    }
};
