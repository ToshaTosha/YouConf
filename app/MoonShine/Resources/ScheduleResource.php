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
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Carbon\Carbon;

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
            BelongsTo::make('Заявка', 'performance', 'title', resource: PerformanceResource::class),
            Text::make('Секция', '', fn($schedule) => $schedule->performance->section->name),
            Text::make('Время начала', 'start_time'),
            Text::make('Продолжительность (мин)', 'duration'),
            Text::make('Место проведения', '', fn($schedule) => $schedule->location->name),
        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        $timeOptions = [];
        $startTime = Carbon::createFromTime(8, 0); // Начало в 8:00
        $endTime = Carbon::createFromTime(20, 0); // Конец в 20:00

        while ($startTime <= $endTime) {
            $timeOptions[$startTime->format('H:i')] = $startTime->format('H:i');
            $startTime->addMinutes(15); // Шаг 15 минут
        }
        $scheduledPerformanceIds = Schedule::pluck('performance_id')->toArray();
        return [
            Box::make([
                BelongsTo::make('Заявка', 'performance', 'title', resource: PerformanceResource::class)
                    ->required()
                    // ->asyncSearch('title') // Включаем асинхронный поиск
                    ->valuesQuery(
                        fn($query) => $query
                            ->where('status_id', 2) // Только принятые заявки
                            ->whereNotIn('id', Schedule::pluck('performance_id')->filter()->toArray()) // Не в расписании
                    ),
                Date::make('Дата', 'date')
                    ->format('Y-m-d')
                    ->required(),
                Select::make('Время начала', 'start_time')
                    ->options($timeOptions) // Варианты времени
                    ->required(),
                Select::make('Продолжительность (мин)', 'duration')
                    ->options([
                        15 => '15 минут',
                        30 => '30 минут',
                        45 => '45 минут',
                        60 => '60 минут',
                    ])
                    ->required(),
                Select::make('Место проведения', 'location_id')
                    ->options(Location::pluck('name', 'id')->toArray())
                    ->required()
                    ->searchable(),
            ]),
        ];
    }

    /**
     * Валидация при сохранении расписания.
     */
    protected function beforeSave(Model $item): void
    {
        // Вычисляем end_time на основе start_time и duration
        $startTime = Carbon::createFromFormat('H:i', $item->start_time);
        $item->end_time = $startTime->copy()->addMinutes($item->duration)->format('H:i');

        // Проверяем, что заявка принята и не в расписании
        if (
            $item->performance->status_id !== 2 ||
            Schedule::where('performance_id', $item->performance_id)->exists()
        ) {
            throw ValidationException::withMessages([
                'performance' => 'Можно выбирать только принятые заявки, которых нет в расписании'
            ]);
        }

        // Проверка на пересечение времени
        $endTime = $startTime->copy()->addMinutes($item->duration);

        $conflictingSchedules = Schedule::whereHas('performance', function ($query) use ($item) {
            $query->where('section_id', $item->performance->section_id);
        })
            ->where('date', $item->date)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    $q->where('start_time', '<', $endTime->format('H:i'))
                        ->where('end_time', '>', $startTime->format('H:i'));
                });
            })
            ->where('id', '!=', $item->id)
            ->exists();

        if ($conflictingSchedules) {
            throw ValidationException::withMessages([
                'start_time' => 'Расписание пересекается с другим мероприятием в этой секции'
            ]);
        }
    }
}
