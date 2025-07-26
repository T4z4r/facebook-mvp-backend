@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ $group->name }}</h2>
    <p>{{ $group->description }}</p>
    @if (!$group->users->contains(Auth::id()))
        <form action="{{ route('groups.join', $group) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">Join Group</button>
        </form>
    @endif
    <a href="{{ route('group_posts.index', $group) }}" class="btn btn-secondary">View Posts</a>
</div>
@endsection