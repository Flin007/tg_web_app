<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\CarColorPivot.
 *
 * @property int $id
 * @property int $car_id Идентификатор автомобиля
 * @property int $color_id Идентификатор цвета
 * @property int $sort_order Порядок сортировки цветов для автомобиля
 */
class CarColorPivot extends Model
{
    use HasFactory;

    protected $table = 'car_color';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'car_id',
        'color_id',
        'sort_order',
    ];

    /**
     * Get the car that this color pivot belongs to.
     */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_id');
    }

    /**
     * Get the color that this pivot belongs to.
     */
    public function color(): BelongsTo
    {
        return $this->belongsTo(CarColor::class, 'color_id');
    }
}
