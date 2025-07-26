@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Posts in {{ $group->name }}</h2>
    <form method="POST" action="{{ route('group_posts.store', $group) }}">
        @csrf
        <div class="form-group">
            <textarea name="content" class="form-control" placeholder="What's on your mind?"></textarea>
        </div>
        <div class="form-group">
            <input type="url" name="image_url" class="form-control" placeholder="Image URL (optional)">
        </div>
        <button type="submit" class="btn btn-primary">Post</button>
    </form>
    @foreach ($posts as $post)
        <div class="card mt-3">
            <div class="card-body">
                <h5>{{ $post->user->name }}</h5>
                <p>{{ $post->content }}</p>
                @if ($post->image_url)
                    <img src="{{ $post->image_url }}" class="img-fluid" alt="Post image">
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
