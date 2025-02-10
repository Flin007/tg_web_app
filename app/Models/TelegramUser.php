<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\TelegramUser.
 *
 * @property int $id
 * @property float balance Баланс
 * @property boolean $is_buy_active Включена ли покупка новых подарков?
 * @property int $buy_type Индентификатор типа покупаемых подарков, @see self::BUY_CHEAP_GIFT_STATUS_ID
 * @property int $user_id Идентификатор юзера в ТГ
 * @property string $username Ник юзера в тг
 * @property string $first_name Имя юзера в тг
 * @property string $last_name Фамилия юзера в тг
 * @property boolean $is_admin админ? Пока не юзаю
 * @property boolean $is_premium есть ли премиум
 * @property boolean $is_bot является ли юзер ботом?
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class TelegramUser extends Model
{
    use HasFactory;
    protected $table = 'telegram_users';
    //Защищаем от хаписи колонку is_admin, чтобы её можно было менять только на прямую в бд
    protected $guarded = ['is_admin'];
}
