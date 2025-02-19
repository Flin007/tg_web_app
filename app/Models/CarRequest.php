<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\TelegramUser.
 *
 * @property int $id
 * @property int $user_id Идентификатор юзера в ТГ
 * @property int $car_id Идентификатор машины
 * @property string $status Статус заявки
 * @property string $data json дата заявки
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class CarRequest extends Model
{
    protected $fillable = [
        'user_id', 'car_id', 'status', 'data',
    ];

    const STATUS_CREATED = 'created';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';

    public static function statuses()
    {
        return [
            self::STATUS_CREATED => 'Создан',
            self::STATUS_IN_PROGRESS => 'В процессе',
            self::STATUS_COMPLETED => 'Завершено',
        ];
    }
}
