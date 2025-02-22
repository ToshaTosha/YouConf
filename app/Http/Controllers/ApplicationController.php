<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\ApplicationVersion;
use App\Models\Chat;
use App\Models\File;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $statuses = Status::all();

        if ($user->role_id == 1) {
            $applications = Application::where('user_id', $user->id)->with(['files', 'section', 'status'])->get();
        } else {
            $applications = Application::with(['files', 'section', 'user', 'status'])->get();
        }

        return inertia('Applications/Index', [
            'applications' => $applications,
            'statuses' => $statuses,
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_id' => 'required|exists:statuses,id',
        ]);

        if (Auth::user()->role_id != 2) {
            return response()->json(['message' => 'Доступ запрещен.'], 403);
        }

        $application = Application::findOrFail($id);
        $application->status_id = $request->status_id;
        $application->save();

        return redirect()->back();
    }

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

    public function update(Request $request, $id)
    {
        DB::transaction(function () use ($request, $id) {
            // Находим текущую заявку
            $application = Application::findOrFail($id);

            // Сохраняем текущее состояние заявки в переменные
            $previousTitle = $application->title;
            $previousDescription = $application->description;
            $previousStatusId = $application->status_id;
            $previousChatId = $application->chat->id;

            // Обновляем заявку новыми данными
            $application->title = $request->title;
            $application->description = $request->description;
            // $application->status_id = $request->status_id; // Раскомментируйте, если нужно обновлять статус
            $application->save();

            // Сохраняем предыдущее состояние заявки в историю
            ApplicationVersion::create([
                'application_id' => $application->id,
                'title' => $previousTitle,
                'description' => $previousDescription,
                'status_id' => $previousStatusId,
                'chat_id' => $previousChatId,
            ]);

            // Создаем новый чат для обновленной заявки
            $newChat = Chat::create([
                'application_id' => $application->id,
            ]);

            // Привязываем новый чат к текущей заявке
            $application->chat()->associate($newChat);
            $application->save();
        });

        Log::info('Request data:', $request->all());

        return redirect()->back()->with('success', 'Заявка обновлена');
    }

    public function show($id)
    {
        $application = Application::with(['files', 'section', 'user', 'status', 'chat.messages.user', 'versions'])->findOrFail($id);

        return inertia('Applications/Show', [
            'application' => $application,
        ]);
    }


    public function edit($id)
    {
        $application = Application::with(['files', 'section', 'user', 'status',])->findOrFail($id);

        return inertia('Applications/Edit', [
            'application' => $application,
        ]);
    }
}
