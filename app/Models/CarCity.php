<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\CarCity.
 *
 * @property int $id
 * @property string $name Название города
 * @property string $slug URL-friendly название города
 * @property boolean $is_active Активность города
 * @property int $sort_order Порядок сортировки городов
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read Collection|Car[] $cars
 */
class CarCity extends Model
{
    use HasFactory;

    protected $table = 'car_cities';

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
     * Get all the cars in this city.
     */
    public function cars(): HasMany
    {
        return $this->hasMany(Car::class, 'city_id');
    }
}
