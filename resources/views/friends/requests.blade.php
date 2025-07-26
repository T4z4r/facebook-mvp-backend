@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4"><i class="bi bi-person-lines-fill text-primary"></i> Friend Requests</h2>

    @if ($requests->isEmpty())
        <div class="alert alert-info">
            <i class="bi bi-info-circle-fill"></i> You have no pending friend requests.
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 g-3">
            @foreach ($requests as $request)
                <div class="col">
                    <div class="card shadow-sm border-0">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title mb-1">
                                    <i class="bi bi-person-circle text-secondary me-1"></i>
                                    {{ $request->user->name }}
                                </h5>
                                <p class="card-text text-muted small mb-0">sent you a friend request</p>
                            </div>
                            <form action="{{ route('friends.accept', $request) }}" method="POST" class="ms-3">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="bi bi-check-circle"></i> Accept
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
