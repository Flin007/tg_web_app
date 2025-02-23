<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\TelegramUser;

use MoonShine\Contracts\UI\ActionButtonContract;
use MoonShine\Laravel\Enums\Action;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\Checkbox;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Url;

/**
 * @extends ModelResource<TelegramUser>
 */
class TelegramUserResource extends ModelResource
{
    protected string $model = TelegramUser::class;

    protected string $title = 'Пользователи из телеграм бота.';
    protected string $description = 'Пользователи из телеграм бота.';

    //Редактор открывается в модальном окне
    protected bool $editInModal = true;

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('TG id','user_id'),
            Url::make('username', 'username', fn($item) => 'https://t.me/'.$item->username )
                ->title(fn(string $url, Url $ctx) => '@' . str($url)->after('t.me/'))
                ->blank(),
            Text::make('Имя','first_name'),
            Text::make('Фамилия','last_name'),
            Checkbox::make('Премиум?', 'is_premium'),
            Checkbox::make('Бот?', 'is_bot'),
            Text::make('Статус', 'status'),
            Checkbox::make('Заблокирован?', 'is_blocked'),
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
                Text::make('TG id','user_id')->readonly()->copy(),
                Text::make('username','username')->readonly()->copy(),
                Text::make('Имя','first_name')->readonly()->copy(),
                Text::make('Фамилия','last_name')->readonly()->copy(),
                Checkbox::make('Заблокирован?', 'is_blocked')
                    ->hint('Можно запретить юзеру запускать веб-приложение')
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
            ID::make(),
        ];
    }

    /**
     * @param TelegramUser $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }

    /**
     * Список возвращаемых кнопок, оставляю только редактирование
     *
     * @return ListOf
     */
    protected function activeActions(): ListOf
    {
        return parent::activeActions()
            ->only(Action::UPDATE);
    }
}
