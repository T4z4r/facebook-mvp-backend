@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Friends</h2>
    <form action="{{ route('friends.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <input type="number" name="friend_id" class="form-control" placeholder="Enter user ID to add friend">
        </div>
        <button type="submit" class="btn btn-primary">Send Friend Request</button>
    </form>
    <h3>Your Friends</h3>
    @foreach ($friends as $friend)
        <p>{{ $friend->name }}</p>
    @endforeach
    <a href="{{ route('friends.requests') }}" class="btn btn-secondary">View Friend Requests</a>
</div>
@endsection
