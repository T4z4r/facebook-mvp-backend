<?php

namespace App\Http\Controllers\Api;

use App\Events\NotificationSent;
use App\Http\Controllers\Controller;
use App\Models\Friend;
use App\Models\Notification;
use Illuminate\Http\Request;

class ApiFriendController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        return auth()->user()->friends()->where('status', 'accepted')->with('friend')->get();
    }

    public function requests()
    {
        return auth()->user()->friends()->where('status', 'pending')->with('friend')->get();
    }

    public function store(Request $request)
    {
        $request->validate(['friend_id' => 'required|exists:users,id']);
        $friend = Friend::create([
            'user_id' => auth()->id(),
            'friend_id' => $request->friend_id,
            'status' => 'pending',
        ]);

        // $notification = Notification::create([
        //     'user_id' => $request->friend_id,
        //     'type' => 'friend_request',
        //     'data' => ['message' => 'Friend request from ' . auth()->user()->name],
        // ]);

        // event(new NotificationSent($notification));

        return response()->json($friend->load('friend'), 201);
    }

    public function accept(Friend $friend)
    {
        $friend->update(['status' => 'accepted']);
        return response()->json($friend->load('friend'), 200);
    }
}
