<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelMarkdown\MarkdownRenderer;

class StaticPage extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'markdown_content',
    ];

    public function getHtmlContentAttribute()
    {
        return app(MarkdownRenderer::class)->toHtml($this->markdown_content);
    }
}
