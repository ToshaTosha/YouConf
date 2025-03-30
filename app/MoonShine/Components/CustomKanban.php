<?php

declare(strict_types=1);

namespace App\MoonShine\Components;

use Closure;
use MoonShine\UI\Components\MoonShineComponent;
use Leeto\MoonShineKanBan\View\Components\KanBanComponent;

/**
 * @method static static make()
 */
final class CustomKanban extends KanBanComponent
{
    protected string $view = 'admin.kanban.custom-kanban';
}
