<?php

namespace App\Repositories;

use App\Models\CarBrand;
use Illuminate\Database\Eloquent\Collection;

class CarBrandRepository extends ModelRepository
{
    public function getClassName(): string
    {
        return CarBrand::class;
    }

    /**
     * Получить все города
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return CarBrand::all();
    }
}
