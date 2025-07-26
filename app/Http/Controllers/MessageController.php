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
        $conversations = Message::where('sender_id', Auth::id())
            ->orWhere('receiver_id', Auth::id())
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($message) {
                return $message->sender_id == Auth::id()
                    ? $message->receiver_id
                    : $message->sender_id;
            });

        return view('messages.index', compact('conversations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $validated['receiver_id'],
            'message' => $validated['message'],
        ]);

        return redirect()->back()->with('status', 'Message sent');
    }

    // API endpoints
    public function apiIndex()
    {
        $conversations = Message::where('sender_id', Auth::id())
            ->orWhere('receiver_id', Auth::id())
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($message) {
                return $message->sender_id == Auth::id()
                    ? $message->receiver_id
                    : $message->sender_id;
            });

        return response()->json($conversations);
    }

    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $validated['receiver_id'],
            'message' => $validated['message'],
        ]);

        return response()->json($message, 201);
    }
}
