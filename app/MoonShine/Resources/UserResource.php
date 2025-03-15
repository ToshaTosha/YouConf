<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Spatie\Permission\Models\Role;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\UI\Fields\Select;

/**
 * @extends ModelResource<User>
 */
class UserResource extends ModelResource
{
    protected string $model = User::class;

    protected string $title = 'Пользователи';

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Имя', 'first_name'),
            Text::make('Фамилия', 'last_name'),
            Text::make('Роли', function (User $user) {
                return $user->getRoleNames()->join(', '); // Отображаем роли пользователя
            }),
        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        $roles = Role::all()->pluck('name', 'id')->toArray();

        return [
            Box::make([
                ID::make(),
                Text::make('Имя', 'first_name')->required(),
                Text::make('Фамилия', 'last_name')->required(),
                Select::make('Роли', 'roles')
                    ->options($roles) // Передаем роли в поле Select
                    ->multiple() // Разрешаем выбор нескольких ролей
                    ->required()
            ]),
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return [
            ID::make(),
            Text::make('Имя', 'first_name'), // Поле имени
            Text::make('Фамилия', 'last_name'), // Поле фамилии
            Text::make('Роли', function (User $user) {
                return $user->getRoleNames()->join(', '); // Отображаем роли пользователя
            }),
        ];
    }

    /**
     * @param User $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }
}
