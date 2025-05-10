<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuillUploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:2048',
            'dir' => 'required|string'
        ]);

        $path = $request->file('file')->store(
            $request->input('dir'),
            'public'
        );

        return response()->json([
            'success' => true,
            'url' => asset("storage/$path"),
            'path' => $path
        ]);
    }
}
