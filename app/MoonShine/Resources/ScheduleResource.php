<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Schedule;
use App\Models\Location;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Contracts\UI\FieldContract;
use MoonShine\UI\Contracts\UI\ComponentContract;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\Time;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;

/**
 * @extends ModelResource<Schedule>
 */
class ScheduleResource extends ModelResource
{
    protected string $model = Schedule::class;

    protected string $title = 'Расписание';

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
            BelongsTo::make('Заявка', 'application', 'title', resource: ApplicationResource::class),
            Date::make('Время начала', 'start_time')
                ->format('H:i'),
            Text::make('Место проведения', '', fn($schedule) => $schedule->location->name),
        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make('Заявка', 'application', 'title', resource: ApplicationResource::class),
            Date::make('Время начала', 'start_time')
                ->format('H:i'),
            Select::make('Место проведения', 'location_id')
                ->options(Location::pluck('name', 'id')->toArray()) // Данные из таблицы locations
                ->required()
                ->searchable(),
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
     * @param Schedule $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }
}
