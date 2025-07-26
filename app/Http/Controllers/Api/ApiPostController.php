<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class ApiPostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        return Post::with('user', 'comments', 'likes')->latest()->paginate(10);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image_url'] = $request->file('image')->store('posts', 'public');
        }

        $post = auth()->user()->posts()->create($data);
        return response()->json($post->load('user', 'comments', 'likes'), 201);
    }

    public function comment(Request $request, Post $post)
    {
        $data = $request->validate(['content' => 'required|string']);
        $comment = $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $data['content'],
        ]);
        return response()->json($comment->load('user'), 201);
    }

    public function like(Post $post)
    {
        $like = $post->likes()->create(['user_id' => auth()->id()]);
        return response()->json($like, 201);
    }

    public function unlike(Post $post)
    {
        $post->likes()->where('user_id', auth()->id())->delete();
        return response()->json(['message' => 'Post unliked'], 200);
    }
}
