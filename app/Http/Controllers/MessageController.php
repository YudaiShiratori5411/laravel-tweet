<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // 受信メッセージと送信メッセージをページネートで取得
        $receivedMessages = Message::where('receiver_id', $userId)->with('sender')->paginate(10);
        $sentMessages = Message::where('sender_id', $userId)->with('receiver')->paginate(10);

        return view('messages.index', compact('receivedMessages', 'sentMessages'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:200',
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        return redirect()->route('messages.index')->with('success', 'メッセージを送信しました！');
    }
}
