<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\CarCity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Car;


use MoonShine\Laravel\Enums\Action;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\BelongsToMany;
use MoonShine\Laravel\Fields\Relationships\HasMany;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\Checkbox;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\DateRange;
use MoonShine\UI\Fields\Field;
use MoonShine\UI\Fields\File;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;

/**
 * @extends ModelResource<Car>
 */
class CarResource extends ModelResource
{
    protected string $model = Car::class;

    protected string $title = 'Машины';

    /**
     * Фильтры для ресурса машин
     *
     * @return iterable
     */
    protected function filters(): iterable
    {
        return [
            Text::make('Vin', 'vin'),
            BelongsTo::make(
                'Город',
                'city',
                formatted: static fn (CarCity $model) => $model->name,
                resource: CarCityResource::class,
            )
                ->valuesQuery(static fn (Builder $q) => $q->select(['id', 'name'])),
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Название', 'title', fn($item) => empty($item->title) ? 'Без названия' : $item->title),
            Text::make('VIN', 'vin'),
            BelongsTo::make(
                'Город',
                'city',
                'name',
                resource: CarCityResource::class
            ),
            BelongsTo::make(
                'Бренд',
                'brand',
                'name',
                resource: CarBrandResource::class
            ),
            Checkbox::make('Статус', 'is_available')
                ->hint('Статус, будет ли отображаться модель')
                ->default(true),
        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                Text::make('Название', 'title')
                    ->hint('Необязательное, если будет пустым склеит из Бренд + Модель'),
                Text::make('Описание', 'description')
                    ->hint('Необязательное поле, если хочется что то дополнить'),
                BelongsTo::make(
                    'Город',
                    'city',
                    'name',
                    resource: CarCityResource::class
                )
                    ->valuesQuery(fn(Builder $query, Field $field) => $query->where('is_active', true))
                    ->required()
                    ->hint('Город, в котором стоит автомобиль, можно создать в меню Города'),
                BelongsTo::make(
                    'Бренд',
                    'brand',
                    'name',
                    resource: CarBrandResource::class
                )
                    ->valuesQuery(fn(Builder $query, Field $field) => $query->where('is_active', true))
                    ->required()
                    ->hint('Бренд авто, можно создать в меню Бренды'),
                BelongsTo::make(
                    'Модель',
                    'model',
                    'name',
                    resource: CarModelResource::class
                )
                    ->valuesQuery(fn(Builder $query, Field $field) => $query->where('is_active', true))
                    ->associatedWith('brand_id')
                    ->asyncOnInit()
                    ->required()
                    ->hint('Бренд авто, можно создать в меню Бренды'),
                BelongsToMany::make(
                    'Цвета',
                    'colors', // Название отношения в модели Car
                    'name', // Поле для отображения в списке выбора (из CarColor)
                    resource: CarColorResource::class
                )
                    ->valuesQuery(fn(Builder $query, Field $field) => $query->where('is_active', true))
                    ->fields([
                        Number::make('Порядок сортировки', 'sort_order')
                            ->default(0)
                            ->hint('Чем меньше число, тем выше приоритет'),
                    ])
                    ->required()
                    ->hint('Выберете до 2 цветов, если машина например Белая с чёрной крышей. Тогда Белый будет с порядковым числом 0, а черный 1, как доп. цвет'),
                HasMany::make('Фотографии', 'photos', resource: CarPhotoResource::class)
                    ->fields([
                        Image::make('Фото', 'path'),
                        Number::make('Порядок сортировки', 'sort_order')
                    ])
                    ->searchable(false) // отключает поле поиска
                    ->creatable(),
                Text::make('Год', 'year')
                    ->required()
                    ->hint('Пример: 2024'),
                Text::make('Двигатель', 'engine')
                    ->required()
                    ->hint('Пример: 1.5 Turbo 147 л.с.'),
                Text::make('Коробка', 'transmission')
                    ->required()
                    ->hint('Пример: Вариатор CVT25'),
                Text::make('Привод', 'drive')
                    ->required()
                    ->hint('Пример: Передний привод'),
                Text::make('Комплектация', 'trim')
                    ->required()
                    ->hint('Пример: 1.5T CVT Ultimate двухцветный'),
                Text::make('Материал и цвет интерьера', 'interior')
                    ->required()
                    ->hint('Пример: Чёрная кожа'),
                Text::make('VIN', 'vin')
                    ->required()
                    ->hint('Пример: LVVDDXYZ*YZ****09'),
                Text::make('Цена', 'price')
                    ->required()
                    ->hint('Пример: 2219900'),
                Text::make('Старая цена', 'old_price')
                    ->hint('Необязательное, Пример: 2989900'),
                Checkbox::make('Статус', 'is_available')
                    ->default(true)
                    ->hint('Будет ли отображаться автомобиль в списке'),
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
     * @param Car $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'city_id' => 'required|exists:car_cities,id',
            'brand_id' => 'required|exists:car_brands,id',
            'model_id' => 'required|exists:car_models,id',
            'year' => 'required|string|max:4',
            'engine' => 'required|string|max:255',
            'transmission' => 'required|string|max:255',
            'drive' => 'required|string|max:255',
            'trim' => 'required|string|max:255',
            'interior' => 'required|string|max:255',
            'vin' => 'required|string|max:255',
            'price' => 'required|numeric',
            'old_price' => 'nullable|numeric',
            'is_available' => 'boolean',
        ];
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
