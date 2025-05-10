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

    public function __construct(
        string $label,
        ?string $column = null,
        ?Closure $formatted = null
    ) {
        parent::__construct($label, $column, $formatted);
    }

    protected function viewData(): array
    {
        return [];
    }
}
