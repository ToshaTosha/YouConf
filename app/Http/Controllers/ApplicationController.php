<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Chat;
use App\Models\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

        Chat::create([
            'application_id' => $application->id,
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('uploads');

                File::create([
                    'path' => $path,
                    'name' => $file->getClientOriginalName(),
                    'fileable_type' => Application::class,
                    'fileable_id' => $application->id,
                ]);
            }
        }

        return redirect()->route('applications.show', ['id' => $application->id])
            ->with('success', 'Заявка успешно создана!');
    }

    public function show($id)
    {
        $application = Application::with(['files', 'section', 'user', 'status', 'chat.messages.user'])->findOrFail($id);

        return inertia('Applications/Show', [
            'application' => $application,
        ]);
    }
}
