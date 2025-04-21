<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\PerformanceKanban;

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
use Illuminate\Support\Facades\Log;
use App\Models\Performance;


/**
 * @extends ModelResource<PerformanceKanban>
 */
class PerformanceKanbanResource extends KanBanResource
{
    protected string $model = Schedule::class;
    protected string $title = 'Расписание выступлений';

    protected bool $createInModal = true;

    protected bool $editInModal = true;

    protected bool $detailInModal = true;

    public function foreignKey(): string
    {
        return 'performance.section_id';
    }

    public function statuses(): Collection
    {
        return Section::all()->mapWithKeys(function (Section $section) {
            return [
                (string)$section->id => $section->name
            ];
        });
    }

    public function modifyListComponent(ComponentContract $component): ComponentContract
    {
        $schedules = Schedule::with('performance')->get();
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
            Text::make('Имя', 'title'),
        ];
    }

    /**
     * @param PerformanceKanban $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }
}
