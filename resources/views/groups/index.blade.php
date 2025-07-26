@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Groups</h2>
    <a href="{{ route('groups.create') }}" class="btn btn-primary">Create Group</a>
    @foreach ($groups as $group)
        <div class="card mt-3">
            <div class="card-body">
                <h5>{{ $group->name }}</h5>
                <p>{{ $group->description }}</p>
                <a href="{{ route('groups.show', $group) }}" class="btn btn-secondary">View</a>
                @if ($group->pivot->role !== 'creator')
                    <form action="{{ route('groups.leave', $group) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger">Leave</button>
                    </form>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
