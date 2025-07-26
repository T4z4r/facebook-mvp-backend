<?php
namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    public function index()
    {
        $friends = Auth::user()->friends;
        return view('friends.index', compact('friends'));
    }

    public function requests()
    {
        $requests = Auth::user()->friendRequests;
        return view('friends.requests', compact('requests'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'friend_id' => 'required|exists:users,id',
        ]);

        Friend::create([
            'user_id' => Auth::id(),
            'friend_id' => $validated['friend_id'],
            'status' => 'pending',
        ]);

        return redirect()->back()->with('status', 'Friend request sent');
    }

    public function accept(Friend $friend)
    {
        $this->authorize('update', $friend);
        $friend->update(['status' => 'accepted']);
        return redirect()->back()->with('status', 'Friend request accepted');
    }

    // API endpoints
    public function apiIndex()
    {
        $friends = Auth::user()->friends;
        return response()->json($friends);
    }

    public function apiRequests()
    {
        $requests = Auth::user()->friendRequests;
        return response()->json($requests);
    }

    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'friend_id' => 'required|exists:users,id',
        ]);

        $friend = Friend::create([
            'user_id' => Auth::id(),
            'friend_id' => $validated['friend_id'],
            'status' => 'pending',
        ]);

        return response()->json($friend, 201);
    }

    public function apiAccept(Friend $friend)
    {
        $this->authorize('update', $friend);
        $friend->update(['status' => 'accepted']);
        return response()->json($friend);
    }
}
