<?php
namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function store(Request $request, Post $post)
    {
        Like::firstOrCreate([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
        ]);

        return redirect()->back()->with('status', 'Post liked');
    }

    public function destroy(Post $post)
    {
        Like::where('user_id', Auth::id())
            ->where('post_id', $post->id)
            ->delete();

        return redirect()->back()->with('status', 'Like removed');
    }

    // API endpoints
    public function apiStore(Request $request, Post $post)
    {
        $like = Like::firstOrCreate([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
        ]);

        return response()->json($like, 201);
    }

    public function apiDestroy(Post $post)
    {
        Like::where('user_id', Auth::id())
            ->where('post_id', $post->id)
            ->delete();

        return response()->json(['message' => 'Like removed']);
    }
}
