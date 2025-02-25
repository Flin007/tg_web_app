<?php

namespace App\Repositories;

use App\Models\TelegramUser;
use Illuminate\Database\Eloquent\Collection;

class TelegramUsersRepository extends ModelRepository
{
    public function getClassName(): string
    {
        return TelegramUser::class;
    }

    /**
     * @param int $userId
     *
     * @return mixed|null
     */
    public function findOneUserByUserId(int $userId): ?TelegramUser
    {
        return $this->createQueryBuilder()
            ->where('user_id', '=', $userId)
            ->first();
    }

    /**
     * Создаем юзера, если приложение было запущено по ссылке, минуя запуск бота
     *
     * @param array $userData
     *
     * @return TelegramUser
     */
    public function createUserFromCheckUserData(array $userData): TelegramUser
    {
        return $this->createQueryBuilder()
            ->create(
                [
                    'user_id' => $userData['id'],
                    'username' => $userData['username'],
                    'first_name' => $userData['first_name'],
                    'last_name' => $userData['last_name'],
                    'is_premium' => $userData['is_premium'],
                    'is_bot' => false,
                    'status' => $userData['allows_write_to_pm'] ? TelegramUser::STATUS_MEMBER : TelegramUser::STATUS_LEFT,
                ]
            );
    }
}
