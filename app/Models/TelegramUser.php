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
 * @property boolean $status Вышел ли из бота, можно ли ему писать?
 * @property boolean $is_blocked Заблокирован ли в приложении
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class TelegramUser extends Model
{
    use HasFactory;
    protected $table = 'telegram_users';

    protected $fillable = [
        'user_id',
        'username',
        'first_name',
        'last_name',
        'is_premium',
        'is_bot',
        'is_blocked',
        'status',
    ];

    // Константы для статусов
    const STATUS_MEMBER = 'member';
    const STATUS_LEFT = 'left';
    const STATUS_KICKED = 'kicked';

    /**
     * Получить список доступных статусов.
     *
     * @return array
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_MEMBER => 'Member',
            self::STATUS_LEFT => 'Left',
            self::STATUS_KICKED => 'Kicked',
        ];
    }
}
