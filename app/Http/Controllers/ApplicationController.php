<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function store(Request $request, $sectionId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $application = new Application();
        $application->title = $request->title;
        $application->description = $request->description;
        $application->user_id = Auth::id();
        $application->section_id = $sectionId;
        $application->status_id = 1;
        $application->save();

        return redirect()->back()->with('success', 'Заявка успешно отправлена!');
    }
}
