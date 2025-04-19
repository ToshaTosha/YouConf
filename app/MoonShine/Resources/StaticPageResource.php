<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\StaticPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\EasyMde\Fields\Markdown;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\File;
use MoonShine\UI\Fields\Preview;

/**
 * @extends ModelResource<StaticPage>
 */
class StaticPageResource extends ModelResource
{
    protected string $model = StaticPage::class;

    protected string $title = 'StaticPages';

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Slug')->required(),
            Text::make('Title')->required(),
            // Preview::make('Files')
            //     ->badge('gray')
            //     ->changeFill(function ($item) {
            //         if (!$item->files) return 'No files';

            //         $images = collect($item->files)
            //             ->filter(fn($file) => str($file['mime'])->contains('image'))
            //             ->count();

            //         $docs = count($item->files) - $images;

            //         return ($images ? "$images images" : "")
            //             . ($docs ? ($images ? ", $docs docs" : "$docs docs") : "");
            //     }),
        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        return [
            Box::make([
                ID::make()->sortable(),
                Text::make('Slug')->required(),
                Text::make('Title')->required(),
                File::make('Files')
                    ->multiple()
                    ->removable()
                    ->allowedExtensions(['jpg', 'png', 'pdf', 'docx'])
                    ->dir('static_pages') // Папка в storage
                    ->keepOriginalFileName(),

                Markdown::make('markdown_content')
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
        ];
    }

    /**
     * @param StaticPage $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }
}
