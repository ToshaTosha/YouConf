<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\StaticPage;

class StaticPageController extends Controller
{
    public function show($slug)
    {
        $page = StaticPage::where('slug', $slug)->firstOrFail();

        return Inertia::render('StaticPage', [
            'title' => $page->title,
            'content' => $page->html_content
        ]);
    }
}
