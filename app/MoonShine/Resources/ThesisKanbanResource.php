<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\ThesisKanban;

use MoonShine\Laravel\Fields\Relationships\BelongsTo;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use Leeto\MoonShineKanBan\Resources\KanBanResource;
use App\Models\Schedule;
use App\Models\Section;
use Illuminate\Support\Collection;
use MoonShine\UI\Fields\Text;
use App\MoonShine\Components\CustomKanban;
use Carbon\Carbon;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Date;
use App\Models\Location;

/**
 * @extends ModelResource<ThesisKanban>
 */
class ThesisKanbanResource extends KanBanResource
{
    protected string $model = Schedule::class;
    protected string $title = 'Расписание выступлений';

    protected bool $createInModal = true;

    protected bool $editInModal = true;

    protected bool $detailInModal = true;

    public function foreignKey(): string
    {
        return 'section_id';
    }

    public function statuses(): Collection
    {
        return Section::all()->mapWithKeys(function (Section $section) {
            return [
                (string)$section->id => $section->name
            ];
        });
    }

    public function sortKey(): string
    {
        return 'start_time'; // или любое другое поле
    }

    public function modifyListComponent(ComponentContract $component): ComponentContract
    {
        $schedules = Schedule::with('thesis')
            ->orderBy('start_time', 'asc') // или другая сортировка
            ->get();
        return CustomKanban::make($this, $schedules);
    }

    public function fields(): array
    {
        return [
            ID::make(),
        ];
    }


    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            // ID::make()->sortable(),
            Text::make('Название', 'title')
                ->required(),

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

                Select::make('Продолжительность (мин)', 'duration')
                    ->options([
                        15 => '15 минут',
                        30 => '30 минут',
                        45 => '45 минут',
                        60 => '60 минут',
                    ])
                    ->required(),

                Select::make('Время начала', 'start_time')
                    ->options($this->getTimeOptions())
                    ->required(),

                Select::make('Место проведения', 'location_id')
                    ->options(Location::pluck('name', 'id')->toArray())
                    ->required()
                    ->searchable(),
            ])
        ];
    }

    private function getTimeOptions(): array
    {
        $options = [];
        $start = Carbon::createFromTime(8, 0);
        $end = Carbon::createFromTime(20, 0);

        while ($start <= $end) {
            $options[$start->format('H:i')] = $start->format('H:i');
            $start->addMinutes(15);
        }

        return $options;
    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return [
            ID::make(),
            Text::make('Имя', 'title'),
        ];
    }

    /**
     * @param ThesisKanban $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }
}
