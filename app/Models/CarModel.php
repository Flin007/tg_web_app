<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\CarModel.
 *
 * @property int $id
 * @property int $brand_id Идентификатор бренда, к которому принадлежит модель
 * @property string $name Название модели
 * @property string $slug URL-friendly название модели
 * @property boolean $is_active Активность модели
 * @property int $sort_order Порядок сортировки моделей
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read CarBrand $brand
 * @property-read Collection|Car[] $cars
 */
class CarModel extends Model
{
    use HasFactory;

    protected $table = 'car_models';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'brand_id',
        'name',
        'slug',
        'is_active',
        'sort_order',
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
     * Get the brand that the model belongs to.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(CarBrand::class, 'brand_id');
    }

    /**
     * Get all the cars for the model.
     */
    public function cars(): HasMany
    {
        return $this->hasMany(Car::class, 'model_id');
    }
}
