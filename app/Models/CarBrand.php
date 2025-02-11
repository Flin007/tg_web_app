<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Carbon;

/**
 * App\Models\CarBrand.
 *
 * @property int $id
 * @property string $name Название бренда
 * @property string $slug URL-friendly название бренда
 * @property boolean $is_active Активность бренда
 * @property int $sort_order Порядок сортировки брендов
 * @property string|null $logo_path Путь к логотипу бренда
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read Collection|CarModel[] $models
 * @property-read Collection|Car[] $cars
 */
class CarBrand extends Model
{
    use HasFactory;

    protected $table = 'car_brands';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'is_active',
        'sort_order',
        'logo_path',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all the models of this brand.
     */
    public function models(): HasMany
    {
        return $this->hasMany(CarModel::class, 'brand_id');
    }

    /**
     * Get all the cars of this brand.
     */
    public function cars(): HasManyThrough
    {
        return $this->hasManyThrough(Car::class, CarModel::class, 'brand_id', 'model_id');
    }
}
