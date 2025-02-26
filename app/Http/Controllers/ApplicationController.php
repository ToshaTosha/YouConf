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

    public function store(Request $request)
    {
        // $request->validate([
        //     'title' => 'required|string|max:255',
        //     'description' => 'required|string',
        //     'section_id' => 'required|exists:sections,id',
        //     'files.*' => 'file|mimes:jpg,png,pdf|max:2048',
        // ]);

        DB::transaction(function () use ($request) {
            // Создаем новую заявку
            $application = Application::create([
                'title' => $request->title,
                'description' => $request->description,
                'user_id' => Auth::id(),
                'status_id' => 1, // Статус "на рассмотрении"
                'section_id' => $request->section_id,
            ]);

            // Создаем чат для заявки
            Chat::create([
                'chatable_type' => Application::class, // Указываем тип модели
                'chatable_id' => $application->id,
            ]);

            // Загружаем файлы, если они есть
            if ($request->hasFile('files')) {
                $this->storeFiles($request->file('files'), $application); // Сохраняем файлы, связанные с Application
            }
        });

        return redirect()->route('applications.index')->with('success', 'Заявка создана');
    }

    public function update(Request $request, $id)
    {
        // $request->validate([
        //     'title' => 'required|string|max:255',
        //     'description' => 'required|string',
        //     'status_id' => 'required|exists:statuses,id',
        //     'files.*' => 'file|mimes:jpg,png,pdf|max:2048',
        // ]);

        DB::transaction(function () use ($request, $id) {
            // Находим текущую заявку
            $application = Application::findOrFail($id);

            // Получаем текущий чат
            $currentChat = $application->chat;

            // Сохраняем текущую версию заявки
            $versionNumber = $application->versions()->count() + 1; // Получаем номер новой версии
            $applicationVersion = $application->versions()->create([
                'title' => $application->title,
                'description' => $application->description,
                'status_id' => $application->status_id,
                'version' => $versionNumber,
            ]);

            $currentChat->chatable_type = ApplicationVersion::class; // Указываем тип модели
            $currentChat->chatable_id = $applicationVersion->id; // Указываем ID новой версии
            $currentChat->save();

            // Обновляем основную заявку
            $application->update($request->only(['title', 'description', 'status_id']));

            Chat::create([
                'chatable_type' => Application::class, // Указываем тип модели
                'chatable_id' => $application->id, // Указываем ID заявки
            ]);

            // Перемещаем файлы из Application в ApplicationVersion
            $this->moveFilesToVersion($application, $applicationVersion);

            // Загружаем новые файлы, если они есть
            if ($request->hasFile('files')) {
                $this->storeFiles($request->file('files'), $applicationVersion); // Передаем новую версию
            }
        });

        return redirect()->back()->with('success', 'Заявка обновлена');
    }

    public function show($id)
    {
        $application = Application::with(['files', 'section', 'user', 'status', 'chat.messages.user', 'versions', 'versions.chat.messages.user', 'versions.files'])->findOrFail($id);

        // Получаем сообщения из чата
        $chat = $application->chat; // Получаем чат, связанный с заявкой
        $messages = $chat ? $chat->messages : []; // Получаем сообщения, если чат существует

        return inertia('Applications/Show', [
            'application' => $application,
            'messages' => $messages, // Передаем сообщения в представление
        ]);
    }


    public function edit($id)
    {
        $application = Application::with(['files', 'section', 'user', 'status',])->findOrFail($id);

        return inertia('Applications/Edit', [
            'application' => $application,
        ]);
    }

    public function history($id)
    {
        // Находим заявку по ID
        $application = Application::with(['versions.chat', 'versions.files']) // Предзагрузка версий, чатов и файлов
            ->findOrFail($id);

        // Получаем все версии заявки
        $applicationVersions = $application->versions;

        return view('applications.history', compact('applicationVersions'));
    }


    protected function storeFiles($files, $fileable)
    {
        foreach ($files as $file) {
            $path = $file->store('uploads');

            File::create([
                'path' => $path,
                'name' => $file->getClientOriginalName(),
                'fileable_type' => get_class($fileable),
                'fileable_id' => $fileable->id,
            ]);
        }
    }

    protected function moveFilesToVersion(Application $application, ApplicationVersion $applicationVersion)
    {
        // Получаем все файлы, связанные с заявкой
        $files = $application->files;

        foreach ($files as $file) {
            // Обновляем запись в таблице files, чтобы она ссылалась на новую версию
            $file->fileable_type = ApplicationVersion::class; // Изменяем тип на ApplicationVersion
            $file->fileable_id = $applicationVersion->id; // Указываем ID новой версии
            $file->save(); // Сохраняем изменения
        }
    }
}
