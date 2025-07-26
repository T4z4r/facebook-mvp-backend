<?php
namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('groups.index', [
            'groups' => auth()->user()->groups()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        auth()->user()->groups()->create($request->only(['name', 'description']));
        return redirect()->route('groups.index')->with('success', 'Group created.');
    }

    public function show(Group $group)
    {
        return view('groups.show', [
            'group' => $group,
            'posts' => $group->posts()->with('user')->latest()->paginate(10),
        ]);
    }

    public function join(Group $group)
    {
        auth()->user()->groups()->attach($group->id);
        return redirect()->route('groups.index')->with('success', 'Joined group.');
    }

    public function leave(Group $group)
    {
        auth()->user()->groups()->detach($group->id);
        return redirect()->route('groups.index')->with('success', 'Left group.');
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

        $group->posts()->create(array_merge($data, ['user_id' => auth()->id()]));
        return redirect()->route('groups.show', $group)->with('success', 'Post created.');
    }
    
    // API endpoints
    public function apiIndex()
    {
        $groups = Auth::user()->groups;
        return response()->json($groups);
    }

    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $group = Group::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'creator_id' => Auth::id(),
        ]);

        $group->users()->attach(Auth::id(), ['role' => 'creator']);
        return response()->json($group, 201);
    }

    public function apiJoin(Group $group)
    {
        $group->users()->attach(Auth::id(), ['role' => 'member']);
        return response()->json(['message' => 'Joined group']);
    }

    public function apiLeave(Group $group)
    {
        $group->users()->detach(Auth::id());
        return response()->json(['message' => 'Left group']);
    }
}
