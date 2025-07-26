<?php
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $friends = $user->friends()->pluck('friend_id')->toArray();
        $posts = Post::whereIn('user_id', array_merge([$user->id], $friends))
                    ->with(['user', 'comments', 'likes'])
                    ->orderBy('created_at', 'desc')
                    ->get();
        return view('posts.index', compact('posts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'image_upload' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $postData = [
            'content' => $validated['content'],
        ];

        if ($request->hasFile('image_upload')) {
            $path = $request->file('image_upload')->store('posts', 'public');
            $postData['image_url'] = Storage::url($path);
        }

        Auth::user()->posts()->create($postData);

        return redirect('/home')->with('status', 'Post created');
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'content' => 'required|string',
            'image_upload' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $updateData = [
            'content' => $validated['content'],
        ];

        if ($request->hasFile('image_upload')) {
            $path = $request->file('image_upload')->store('posts', 'public');
            $updateData['image_url'] = Storage::url($path);
        }

        $post->update($updateData);

        return redirect('/home')->with('status', 'Post updated');
    }


    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();
        return redirect('/home')->with('status', 'Post deleted');
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
