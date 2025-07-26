<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Group;
use Illuminate\Http\Request;

class ApiSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->query('q');
        $users = User::where('name', 'like', "%$query%")
            ->orWhere('email', 'like', "%$query%")
            ->get();
        $groups = Group::where('name', 'like', "%$query%")
            ->orWhere('description', 'like', "%$query%")
            ->get();

        return response()->json(['users' => $users, 'groups' => $groups]);
    }
}
