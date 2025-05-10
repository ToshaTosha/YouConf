<?php

declare(strict_types=1);

namespace App\MoonShine\Components;

use Closure;
use MoonShine\UI\Components\MoonShineComponent;

/**
 * @method static static make()
 */
final class QuillEditor extends MoonShineComponent
{
    protected string $view = 'admin.components.quill-editor';

    protected function viewData(): array
    {
        return [];
    }
}
