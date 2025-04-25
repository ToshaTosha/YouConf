<?php

namespace App\Http\Controllers;

use App\Models\Performance;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\Auth;

class FileController extends Controller
{
    public function store(Request $request, Performance $performance)
    {
        $request->validate([
            'files.*' => 'required|file|mimes:jpg,png,pdf,doc,docx|max:10240'
        ]);

        $mediaItems = [];

        foreach ($request->file('files') as $file) {
            $mediaItems[] = $performance->addMedia($file)
                ->withCustomProperties([
                    'original_name' => $file->getClientOriginalName(),
                    'uploaded_by' => Auth::user()->id
                ])
                ->toMediaCollection('attachments');
        }

        return response()->json($mediaItems);
    }

    public function destroy(Performance $performance, Media $media)
    {
        $media->delete();

        return response()->json(['message' => 'File deleted successfully']);
    }
}
