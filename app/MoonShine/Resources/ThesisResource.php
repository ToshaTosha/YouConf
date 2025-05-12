<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Thesis;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Fields\Text;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\UI\Fields\Preview;

/**
 * @extends ModelResource<Thesis>
 */
class ThesisResource extends ModelResource
{
    protected string $model = Thesis::class;

    protected string $title = 'Тезисы';

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
            Text::make('Название', 'title'),
            BelongsTo::make('Пользователь', 'user', fn($user) => $user->first_name . ' ' . $user->last_name),
            BelongsTo::make('Секция', 'section', resource: SectionResource::class, formatted: 'name'),
            // BelongsTo::make('Статус', 'status', resource: StatusResource::class, formatted: 'name'),
            Preview::make('Статус', 'status.name')
                ->badge(fn($status) => match ($status) {
                    'Принято' => 'green',       // Замените на реальные названия статусов
                    'На доработку' => 'yellow',
                    'Отклонено' => 'red',
                    default => 'gray',
                }),
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
                BelongsTo::make('Секция', 'section', resource: SectionResource::class, formatted: 'name'),
                BelongsTo::make('Статус', 'status', resource: StatusResource::class, formatted: 'name'),
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
            Text::make('Название', 'title'),
            Text::make('Описание', 'description'),
            BelongsTo::make('Пользователь', 'user', fn($user) => $user->first_name . ' ' . $user->last_name),
            Text::make('Соавторы', 'co_authors', fn($value) => $value->co_authors ?: '-'),
            BelongsTo::make('Секция', 'section', resource: SectionResource::class, formatted: 'name'),
            BelongsTo::make('Статус', 'status', resource: StatusResource::class, formatted: 'name'),
            Preview::make('Связанные файлы', 'files')->morphTo('fileable'),
        ];
    }

    /**
     * @param Thesis $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }
}
