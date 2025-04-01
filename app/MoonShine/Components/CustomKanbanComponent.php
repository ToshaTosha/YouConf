<?php

namespace Leeto\MoonShineKanBan\View\Components;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Leeto\MoonShineKanBan\Resources\KanBanResource;
use MoonShine\UI\Components\ActionGroup;
use MoonShine\UI\Components\MoonShineComponent;
use Leeto\MoonShineKanBan\View\Components\KanBanComponent;

class CustomKanbanComponent extends KanBanComponent
{
    protected string $view = 'moonshine-kanban::components.kanban-component';
}
