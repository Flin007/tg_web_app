<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\CarPhoto;

use MoonShine\Laravel\Enums\Action;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\Field;
use MoonShine\UI\Fields\File;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;

/**
 * @extends ModelResource<CarPhoto>
 */
class CarPhotoResource extends ModelResource
{
    protected string $model = CarPhoto::class;

    protected string $title = 'Фотографии всех машин';

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Image::make('Фото', 'path'),
            BelongsTo::make(
                'Машина',
                'car',
                fn($item) => '#'.$item->id.', '.(empty($item->title) ? 'Без названия' : $item->title),
                resource: CarBrandResource::class
            )
        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        return [
            Box::make([
                File::make('Файл', 'path')
                    ->disk('public')
                    ->dir('car_photos')
                    ->removable(),
                BelongsTo::make(
                    'Машина',
                    'car',
                    fn($item) => '#'.$item->id.', '.(empty($item->title) ? 'Без названия' : $item->title),
                    resource: CarBrandResource::class
                )
                    ->valuesQuery(fn(Builder $query, Field $field) => $query->where('is_available', true))
                    ->required()
                    ->hint('К какой машине загружаем фотку'),
                Number::make('Порядок сортировки', 'sort_order')
                    ->default(0),
            ])
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return [
            ID::make(),
        ];
    }

    /**
     * @param CarPhoto $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }

    /**
     * Список возвращаемых кнопок, убираем просмотр
     *
     * @return ListOf
     */
    protected function activeActions(): ListOf
    {
        return parent::activeActions()
            ->except(Action::VIEW);
    }
}
