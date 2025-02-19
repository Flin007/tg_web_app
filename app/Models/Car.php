<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Car.
 *
 * @property int $id
 * @property int $city_id Идентификатор города, где находится автомобиль
 * @property int $brand_id Идентификатор бренда автомобиля
 * @property int $model_id Идентификатор модели автомобиля
 * @property string $year Год выпуска автомобиля
 * @property string $engine Информация о двигателе (например, "1.5 116 л.с.")
 * @property string $transmission Информация о трансмиссии (например, "Вариатор CVT18")
 * @property string $drive Тип привода (например, "Передний привод")
 * @property string $trim Комплектация (например, "1.5 CVT Classic")
 * @property string $color Цвет автомобиля
 * @property string $interior Материал и цвет интерьера
 * @property string $vin VIN номер автомобиля
 * @property float $price Актуальная стоимость
 * @property float $old_price Прошлая стоимость (если была)
 * @property string|null $description Описание автомобиля
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read CarCity $city
 * @property-read CarBrand $brand
 * @property-read CarModel $model
 */
class Car extends Model
{
    use HasFactory;

    protected $table = 'cars';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'city_id',
        'brand_id',
        'model_id',
        'year',
        'engine',
        'transmission',
        'drive',
        'trim',
        'color',
        'interior',
        'vin',
        'price',
        'old_price',
        'description'
    ];

    /**
     * Get the city that the car belongs to.
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(CarCity::class, 'city_id');
    }

    /**
     * Get the brand of the car through its model.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(CarBrand::class, 'brand_id');
    }

    /**
     * Get the model that the car belongs to.
     */
    public function model(): BelongsTo
    {
        return $this->belongsTo(CarModel::class, 'model_id');
    }

    /**
     * Get all the photos for the car.
     */
    public function photos(): HasMany
    {
        return $this->hasMany(CarPhoto::class, 'car_id');
    }

    /**
     * Get all the colors for the car.
     */
    public function colors(): BelongsToMany
    {
        return $this->belongsToMany(CarColor::class, 'car_color', 'car_id', 'color_id')
            ->withPivot('sort_order');
    }

    /**
     * @param string $vin
     *
     * @return string
     */
    public static function maskVin(string $vin): string
    {
        if (strlen($vin) !== 17) {
            return $vin; // Возвращаем исходный VIN, если длина не 17 символом
        }

        // Маскируем VIN
        return substr($vin, 0, 7) . '*' . substr($vin, 8, 2) . '****' . substr($vin, -2);
    }

    public const AVAILABLE_FILTERS = ['city', 'brand', 'model'];

    /**
     * Фильтруем машины по переданным параметрам
     *
     * @param Builder $query
     * @param array $filters
     *
     * @return void
     */
    public function scopeFilter(Builder $query, array $filters): void
    {
        //Фильтруем city
        if (isset($filters['city']) ?? false) {
            $query->where('city_id', $filters['city']);
        }
        //Фильтруем brand
        if (isset($filters['brand']) ?? false) {
            $query->where('brand_id', $filters['brand']);
        }
        //Фильтруем model
        if (isset($filters['model']) ?? false) {
            $query->where('model_id', $filters['model']);
        }
    }
}
