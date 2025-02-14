<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\CarCity;

use MoonShine\Laravel\Enums\Action;
use MoonShine\Laravel\Fields\Slug;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\Checkbox;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;

/**
 * @extends ModelResource<CarCity>
 */
class CarCityResource extends ModelResource
{
    protected string $model = CarCity::class;

    protected string $title = 'Города';

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Название', 'name'),
            Slug::make('Slug', 'slug'),
            Number::make('Порядковый номер', 'sort_order'),
            Checkbox::make('Статус', 'is_active'),
        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        return [
            Box::make([
                ID::make()->sortable(),
                Text::make('Название', 'name')
                    ->required()
                    ->hint('Название города'),
                Slug::make('Slug', 'slug')
                    ->required()
                    ->hint('Сокращённый нейминг для url, например: msk, spb'),
                Number::make('Порядковый номер', 'sort_order')
                    ->buttons()
                    ->min(0)
                    ->default(0)
                    ->hint('Очередность отображения, по умолчанию у всех 0'),
                Checkbox::make('Статус', 'is_active')
                    ->hint('Статус, будет ли отображаться город')
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
            Number::make('Порядковый номер', 'sort_order'),
            Checkbox::make('Статус', 'is_active'),
        ];
    }

    /**
     * @param CarCity $item
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
