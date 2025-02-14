<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\CarBrand;

use MoonShine\Laravel\Fields\Slug;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\Checkbox;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;

/**
 * @extends ModelResource<CarBrand>
 */
class CarBrandResource extends ModelResource
{
    protected string $model = CarBrand::class;

    protected string $title = 'Бренды';

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Название', 'name'),
            Slug::make('Slug', 'slug'),
            Image::make('Логотип', 'logo_path'),
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
                    ->reactive(null, false, 1000, 0)
                    ->hint('Название бренда, должно быть уникальным'),
                Slug::make('Slug', 'slug')
                    ->locale('en')
                    ->from('name')
                    ->live()
                    ->required()
                    ->hint('Уникальная строка для url, генерируется сама от заданного названия. Можете указать свою.'),
                Image::make('Логотип', 'logo_path')
                    ->nullable()
                    ->hint('Логотип бренда'),
                Number::make('Порядковый номер', 'sort_order')
                    ->buttons()
                    ->min(0)
                    ->default(0)
                    ->hint('Очередность отображения, по умолчанию у всех 0'),
                Checkbox::make('Статус', 'is_active')
                    ->hint('Статус, будет ли отображаться бренд')
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
            Image::make('Логотип', 'logo_path'),
            Number::make('Порядковый номер', 'sort_order'),
            Checkbox::make('Статус', 'is_active'),
        ];
    }

    /**
     * @param CarBrand $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }
}
