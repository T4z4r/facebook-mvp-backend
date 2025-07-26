@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Post</h2>
    <form method="POST" action="{{ route('posts.update', $post) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <textarea name="content" class="form-control">{{ $post->content }}</textarea>
        </div>
        <div class="form-group">
            <input type="url" name="image_url" class="form-control" value="{{ $post->image_url }}" placeholder="Image URL (optional)">
        </div>
        <button type="submit" class="btn btn-primary">Update Post</button>
    </form>
</div>
@endsection
