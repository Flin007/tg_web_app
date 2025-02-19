<?php

namespace App\Repositories;

use App\Models\CarModel;
use Illuminate\Database\Eloquent\Collection;

class CarModelRepository extends ModelRepository
{
    public function getClassName(): string
    {
        return CarModel::class;
    }

    /**
     * Получить все города
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return CarModel::all();
    }
}
