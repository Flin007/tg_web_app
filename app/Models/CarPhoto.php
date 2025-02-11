<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\CarPhoto.
 *
 * @property int $id
 * @property int $car_id Идентификатор автомобиля, к которому привязана фотография
 * @property string $path Путь к файлу фотографии
 * @property int $sort_order Порядок сортировки фотографий
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read Car $car
 */
class CarPhoto extends Model
{
    use HasFactory;

    protected $table = 'car_photos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'car_id',
        'path',
        'sort_order'
    ];

    /**
     * Get the car that this photo belongs to.
     */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_id');
    }
}
