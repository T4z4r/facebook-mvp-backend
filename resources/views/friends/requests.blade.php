@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Friend Requests</h2>
    @foreach ($requests as $request)
        <p>{{ $request->user->name }} sent you a friend request</p>
        <form action="{{ route('friends.accept', $request) }}" method="POST">
            @csrf
            @method('PUT')
            <button type="submit" class="btn btn-primary">Accept</button>
        </form>
    @endforeach
</div>
@endsection
