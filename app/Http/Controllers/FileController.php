<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FileController extends Controller
{
    public function store(Request $request)
    {
        $files = $request->file('files');

        if ($files) {
            foreach ($files as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = Storage::putFileAs(
                    'uploads',
                    $file,
                    $filename
                );
                Log::info(Auth::id());

                $newFile = new File([
                    'filename' => $filename,
                    'path' => $path,
                    'user_id' => Auth::id(),
                ]);
                $newFile->save();
            }

            return response()->json(['message' => 'Файлы успешно загружены'], 200);
        } else {
            return response()->json(['error' => 'Не удалось загрузить файлы'], 400);
        }
    }
}
