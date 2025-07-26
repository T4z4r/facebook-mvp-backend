<?php
namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupPostController extends Controller
{
    public function index(Group $group)
    {
        $posts = $group->posts()->with(['user'])->get();
        return view('group_posts.index', compact('group', 'posts'));
    }

    public function store(Request $request, Group $group)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'image_url' => 'nullable|url',
        ]);

        $post = $group->posts()->create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
            'image_url' => $validated['image_url'],
        ]);

        return redirect()->back()->with('status', 'Post created');
    }

    // API endpoints
    public function apiIndex(Group $group)
    {
        $posts = $group->posts()->with(['user'])->get();
        return response()->json($posts);
    }

    public function apiStore(Request $request, Group $group)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'image_url' => 'nullable|url',
        ]);

        $post = $group->posts()->create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
            'image_url' => $validated['image_url'],
        ]);

        return response()->json($post, 201);
    }
}
