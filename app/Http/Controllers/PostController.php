<?php
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('posts.index', [
            'posts' => Post::with('user', 'comments', 'likes')->latest()->paginate(10),
        ]);
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
        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    }

    public function comment(Request $request, Post $post)
    {
        $request->validate(['content' => 'required|string']);
        $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);
        return redirect()->route('posts.index')->with('success', 'Comment added.');
    }

    public function like(Post $post)
    {
        $post->likes()->create(['user_id' => auth()->id()]);
        return redirect()->route('posts.index')->with('success', 'Post liked.');
    }

    public function unlike(Post $post)
    {
        $post->likes()->where('user_id', auth()->id())->delete();
        return redirect()->route('posts.index')->with('success', 'Post unliked.');
    }
    // API endpoints
    public function apiIndex()
    {
        $user = Auth::user();
        $friends = $user->friends()->pluck('friend_id')->toArray();
        $posts = Post::whereIn('user_id', array_merge([$user->id], $friends))
                    ->with(['user', 'comments', 'likes'])
                    ->orderBy('created_at', 'desc')
                    ->get();
        return response()->json($posts);
    }

    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'image_url' => 'nullable|url',
        ]);

        $post = Auth::user()->posts()->create($validated);
        return response()->json($post, 201);
    }

    public function apiUpdate(Request $request, Post $post)
    {
        $this->authorize('update', $post);
        $validated = $request->validate([
            'content' => 'required|string',
            'image_url' => 'nullable|url',
        ]);

        $post->update($validated);
        return response()->json($post);
    }

    public function apiDestroy(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();
        return response()->json(['message' => 'Post deleted']);
    }
}
