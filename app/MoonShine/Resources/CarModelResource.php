<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\CarModel;

use MoonShine\Laravel\Enums\Action;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\HasOne;
use MoonShine\Laravel\Fields\Slug;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\Checkbox;
use MoonShine\UI\Fields\Field;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;

/**
 * @extends ModelResource<CarModel>
 */
class CarModelResource extends ModelResource
{
    protected string $model = CarModel::class;

    protected string $title = 'CarModels';

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Название', 'name'),
            Slug::make('Slug', 'slug'),
            BelongsTo::make(
                'Бренд',
                'brand',
                'name',
                resource: CarBrandResource::class
            ),
            Number::make('Порядковый номер', 'sort_order'),
            Checkbox::make('Статус', 'is_active')
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
                Text::make('Название', 'name')
                    ->required()
                    ->hint('Название модели: C5, C5 NEW, J7...'),
                Slug::make('Slug', 'slug')
                    ->required()
                    ->hint('Сокращённый нейминг для url: c5, c5-new, j7...'),
                BelongsTo::make(
                    'Бренд',
                    'brand',
                    'name',
                    resource: CarBrandResource::class
                )
                    ->valuesQuery(fn(Builder $query, Field $field) => $query->where('is_active', true))
                    ->required()
                    ->hint('К какому бренду принадлежит модель, созда можно в меню Бренды'),
                Number::make('Порядковый номер', 'sort_order')
                    ->buttons()
                    ->min(0)
                    ->default(0)
                    ->hint('Очередность отображения, по умолчанию у всех 0'),
                Checkbox::make('Статус', 'is_active')
                    ->hint('Статус, будет ли отображаться модель')
                    ->default(true),
            ])
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Название', 'name'),
            Slug::make('Slug', 'slug'),
            BelongsTo::make(
                'Бренд',
                'brand',
                'name',
                resource: CarBrandResource::class
            ),
            Number::make('Порядковый номер', 'sort_order'),
            Checkbox::make('Статус', 'is_active')
        ];
    }

    /**
     * @param CarModel $item
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
