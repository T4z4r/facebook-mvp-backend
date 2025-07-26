@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create Group</h2>
    <form method="POST" action="{{ route('groups.store') }}">
        @csrf
        <div class="form-group">
            <label for="name">Group Name</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Create Group</button>
    </form>
</div>
@endsection
