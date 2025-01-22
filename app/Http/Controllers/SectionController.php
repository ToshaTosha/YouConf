<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Section;
use Illuminate\Support\Facades\Log;

class SectionController extends Controller
{
    public function index()
    {
        Log::info(Section::all());
        return Inertia::render('Sections/Index', [
            'sections' => Section::all(),
        ]);
    }

    public function show(Section $section)
    {
        return Inertia::render('Sections/Show', [
            'section' => $section,
        ]);
    }
}
