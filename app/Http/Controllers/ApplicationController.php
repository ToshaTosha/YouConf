<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\ApplicationVersion;
use App\Models\Chat;
use App\Models\File;
use App\Models\Section;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;



use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    public function index()
    {
        $user = User::findOrFail(Auth::user()->id);
        $statuses = Status::all();

        if ($user && $user->hasRole('participant')) {
            // Если пользователь участник, показываем только его заявки
            $applications = Application::where('user_id', $user->id)
                ->with(['files', 'section', 'status'])
                ->get();
        } else {
            // Если пользователь эксперт или администратор, показываем все заявки
            $applications = Application::with(['files', 'section', 'user', 'status'])
                ->get();
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

        $user = User::findOrFail(Auth::user()->id);

        // Проверяем, является ли пользователь экспертом
        if (!$user->hasRole('expert')) {
            return response()->json(['message' => 'Доступ запрещен.'], 403);
        }

        // Обновляем статус заявки
        $application = Application::findOrFail($id);
        $application->status_id = $request->status_id;
        $application->save();

        return redirect()->back();
    }

    public function apply(Request $request)
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

        return redirect()->intended(route('user.show', ['id' => Auth::user()->id]));
    }

    public function update(Request $request, $id)
    {
        DB::transaction(function () use ($request, $id) {
            // Находим текущую заявку
            $application = Application::findOrFail($id);

            // Обновляем основную заявку
            $application->update($request->only(['title', 'description', 'status_id']));

            // Загружаем новые файлы, если они есть
            $files = $request->input('files'); // Получаем массив файлов (существующие файлы)
            $newFiles = $request->file('files'); // Получаем новые файлы

            // Обрабатываем существующие файлы
            if ($files) {
                foreach ($files as $file) {
                    $fileData = json_decode($file, true); // Декодируем JSON
                    if (is_array($fileData)) {
                        // Это существующий файл, обновляем его связь
                        $existingFile = File::find($fileData['id']);
                        if ($existingFile) {
                            $existingFile->update([
                                'fileable_id' => $application->id,
                                'fileable_type' => get_class($application),
                            ]);
                        }
                    }
                }
            }

            // Обрабатываем новые файлы
            if ($newFiles) {
                foreach ($newFiles as $file) {
                    if ($file instanceof \Illuminate\Http\UploadedFile) {
                        // Это новый файл, загружаем его
                        $this->storeFiles([$file], $application);
                    }
                }
            }
        });

        return redirect()->intended(route('user.show', ['id' => Auth::user()->id]));
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


    public function create($section_id = null)
    {
        $section = Section::findOrFail($section_id);
        return inertia('Applications/ApplicationFormPage', [
            'section_id' => $section_id,
            'section_name' => $section->name,
        ]);
    }


    public function edit($id)
    {
        $application = Application::with(['files', 'section', 'user', 'status',])->findOrFail($id);

        return inertia('Applications/ApplicationFormPage', [
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
