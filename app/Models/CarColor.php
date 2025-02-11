<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\CarColor.
 *
 * @property int $id
 * @property string $name Название цвета
 * @property string|null $hex_code HEX код цвета
 * @property boolean $is_active Активность цвета
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read Collection|Car[] $cars
 */
class CarColor extends Model
{
    use HasFactory;

    protected $table = 'car_colors';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'hex_code',
        'is_active',
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
     * Get all the cars that have this color.
     */
    public function cars(): BelongsToMany
    {
        return $this->belongsToMany(Car::class, 'car_color', 'color_id', 'car_id')
            ->withPivot('sort_order');
    }
}
