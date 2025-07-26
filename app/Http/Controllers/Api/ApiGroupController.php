<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupPost;
use Illuminate\Http\Request;

class ApiGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        return auth()->user()->groups()->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $group = auth()->user()->groups()->create($request->only(['name', 'description']));
        return response()->json($group, 201);
    }

    public function posts(Group $group)
    {
        return $group->posts()->with('user')->latest()->paginate(10);
    }

    public function storePost(Request $request, Group $group)
    {
        $data = $request->validate([
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image_url'] = $request->file('image')->store('group_posts', 'public');
        }

        $post = $group->posts()->create(array_merge($data, ['user_id' => auth()->id()]));
        return response()->json($post->load('user'), 201);
    }

    public function join(Group $group)
    {
        auth()->user()->groups()->attach($group->id);
        return response()->json(['message' => 'Joined group'], 200);
    }

    public function leave(Group $group)
    {
        auth()->user()->groups()->detach($group->id);
        return response()->json(['message' => 'Left group'], 200);
    }
}
