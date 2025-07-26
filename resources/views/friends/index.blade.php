@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-4">
        <h2 class="mb-3">Add a Friend</h2>
        <form action="{{ route('friends.store') }}" method="POST" class="row g-2 align-items-end">
            @csrf
            <div class="col-md-6">
                <label for="friend_id" class="form-label">Enter User ID</label>
                <input type="number" name="friend_id" id="friend_id" class="form-control" placeholder="e.g. 102" required>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-person-plus-fill"></i> Send Friend Request
                </button>
            </div>
        </form>
    </div>

    <div class="mb-4">
        <h3 class="mb-3">Your Friends</h3>
        @if ($friends->isEmpty())
            <p class="text-muted">You have no friends added yet.</p>
        @else
            <ul class="list-group">
                @foreach ($friends as $friend)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-person-fill text-primary me-2"></i>{{ $friend->name }}</span>
                        {{-- You could add "Unfriend" or "View Profile" options here --}}
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <a href="{{ route('friends.requests') }}" class="btn btn-outline-secondary">
        <i class="bi bi-envelope-paper"></i> View Friend Requests
    </a>
</div>
@endsection
