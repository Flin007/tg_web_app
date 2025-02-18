<?php

namespace App\Repositories;

use App\Models\CarCity;
use Illuminate\Database\Eloquent\Collection;

class CarCityRepository extends ModelRepository
{
    public function getClassName(): string
    {
        return CarCity::class;
    }

    /**
     * Получить все города
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return CarCity::all();
    }
}
