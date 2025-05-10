<?php

declare(strict_types=1);

namespace App\MoonShine\Components;

use Closure;
use MoonShine\UI\Components\MoonShineComponent;
use MoonShine\UI\Fields\Textarea;

/**
 * @method static static make()
 */
final class QuillEditor extends Textarea
{
    protected string $view = 'admin.components.quill-editor';

    public function save(mixed $value): mixed
    {
        return $value;
    }
    protected string $uploadDir = 'quill-uploads';
    protected bool $withFiles = false;

    public function __construct(
        string $label,
        ?string $column = null,
        ?Closure $formatted = null
    ) {
        parent::__construct($label, $column, $formatted);
    }

    // public function withFiles(string $dir = 'quill-uploads'): self
    // {
    //     $this->withFiles = true;
    //     $this->uploadDir = $dir;
    //     return $this;
    // }

    // public function save(mixed $value): mixed
    // {
    //     if ($this->withFiles) {
    //         return $this->processContentWithFiles($value);
    //     }
    //     return $value;
    // }

    // protected function processContentWithFiles(string $content): array
    // {
    //     return [
    //         'markdown_content' => $content,
    //         'files' => $this->extractFilesFromContent($content)
    //     ];
    // }

    // protected function extractFilesFromContent(string $content): array
    // {
    //     $files = [];
    //     $pattern = '/!\[.*?\]\((.*?)\)/';
    //     preg_match_all($pattern, $content, $matches);

    //     if (!empty($matches[1])) {
    //         foreach ($matches[1] as $filePath) {
    //             if (str_starts_with($filePath, 'storage/')) {
    //                 $files[] = str_replace('storage/', '', $filePath);
    //             }
    //         }
    //     }

    //     return $files;
    // }

    protected function viewData(): array
    {
        return [];
    }
}
