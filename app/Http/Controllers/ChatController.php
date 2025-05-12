<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Thesis;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function storeMessage(Request $request, Chat $chat)
    {
        $message = $chat->messages()->create([
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return redirect()->back();
    }

    public function showChat(Thesis $thesis)
    {
        $chat = $thesis->chat;
        $messages = $chat->messages()->with('user')->get();

        return inertia('Chat/Show', [
            'chat' => $chat,
            'messages' => $messages,
        ]);
    }
}
