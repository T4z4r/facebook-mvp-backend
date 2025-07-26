<?php
namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Auth::user()->groups;
        return view('groups.index', compact('groups'));
    }

    public function create()
    {
        return view('groups.create');
    }

    public function store(Request $request)
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
        return redirect('/groups')->with('status', 'Group created');
    }

    public function show(Group $group)
    {
        return view('groups.show', compact('group'));
    }

    public function join(Group $group)
    {
        $group->users()->attach(Auth::id(), ['role' => 'member']);
        return redirect()->back()->with('status', 'Joined group');
    }

    public function leave(Group $group)
    {
        $group->users()->detach(Auth::id());
        return redirect()->back()->with('status', 'Left group');
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
