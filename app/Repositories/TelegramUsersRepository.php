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
}
