<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;

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

    protected bool $createInModal = true;

    protected bool $editInModal = true;

    protected bool $detailInModal = true;

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Имя', 'first_name')->sortable(),
            Text::make('Фамилия', 'last_name')->sortable(),
            Text::make('Role', '', fn($user) => $user->roles->first()?->name),
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
                Select::make('Role', 'role_id')
                    ->options(Role::pluck('name', 'id')->toArray())
                    ->required()
                    ->searchable(),
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
        ];
    }
}
