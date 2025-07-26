@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Home Feed</h2>
    <form method="POST" action="{{ route('posts.store') }}">
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
                <p>{{ $post->likes->count() }} Likes | {{ $post->comments->count() }} Comments</p>
                @if (Auth::user()->id === $post->user_id)
                    <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-secondary">Edit</a>
                    <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                @endif
                <form action="{{ route('likes.store', $post) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-primary">Like</button>
                </form>
                <form action="{{ route('likes.destroy', $post) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-primary">Unlike</button>
                </form>
                <form action="{{ route('comments.store', $post) }}" method="POST" class="mt-2">
                    @csrf
                    <div class="form-group">
                        <input type="text" name="content" class="form-control" placeholder="Add a comment">
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary">Comment</button>
                </form>
                @foreach ($post->comments as $comment)
                    <p class="mt-2"><strong>{{ $comment->user->name }}:</strong> {{ $comment->content }}</p>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
@endsection
