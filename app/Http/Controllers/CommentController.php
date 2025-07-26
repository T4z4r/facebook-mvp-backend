<?php
namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $comment = $post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
        ]);

        return redirect()->back()->with('status', 'Comment added');
    }

    // API endpoint
    public function apiStore(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $comment = $post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
        ]);

        return response()->json($comment, 201);
    }
}
