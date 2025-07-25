@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">

            {{-- Post creation card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex align-items-center">
                    <i class="bi bi-pencil-square fs-4 text-primary me-2"></i>
                    <h5 class="mb-0">Create a Post</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Post content --}}
                        <div class="mb-3">
                            <textarea name="content" rows="3"
                                class="form-control @error('content') is-invalid @enderror"
                                placeholder="What's on your mind?"></textarea>
                            @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Image picker --}}
                        <div class="mb-3">
                            <label for="image_upload" class="form-label">Attach an image</label>
                            <input type="file" name="image_upload" id="image_upload" accept="image/*"
                                class="form-control @error('image_upload') is-invalid @enderror"
                                onchange="previewImage(event)">
                            @error('image_upload')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="image_preview" class="mt-3 d-none">
                                <img id="preview" class="img-fluid rounded shadow-sm" style="max-height: 300px;">
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-send-fill"></i> Post
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Posts feed --}}
            @foreach ($posts as $post)
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                                    <h6 class="fw-bold mb-1"><i class="bi bi-person-circle"></i> {{ $post->user->name }}</h6>

                </div>
                <div class="card-body">
                    <p class="mb-2">{{ $post->content }}</p>
                    @if ($post->image_url)
                    <img src="{{ $post->image_url }}" class="img-fluid rounded mb-2" alt="Post image">
                    @endif

                    <p class="text-muted small mb-2">
                        <i class="bi bi-heart-fill text-danger"></i> {{ $post->likes->count() }} Likes |
                        <i class="bi bi-chat-left-text-fill text-primary"></i> {{ $post->comments->count() }} Comments
                    </p>

                    {{-- Like / Unlike --}}
                    <div class="d-flex flex-wrap gap-2 mb-2">
                        <form action="{{ route('likes.store', $post) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-primary"><i
                                    class="bi bi-hand-thumbs-up-fill"></i> Like</button>
                        </form>
                        <form action="{{ route('likes.destroy', $post) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-secondary"><i
                                    class="bi bi-hand-thumbs-down-fill"></i> Unlike</button>
                        </form>

                        @if (Auth::id() === $post->user_id)
                        <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-warning"><i
                                class="bi bi-pencil"></i> Edit</a>
                        <form action="{{ route('posts.destroy', $post) }}" method="POST"
                            onsubmit="return confirm('Are you sure?')" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash3"></i>
                                Delete</button>
                        </form>
                        @endif
                    </div>

                    {{-- Comment form --}}
                    <form action="{{ route('comments.store', $post) }}" method="POST" class="mt-3">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="content" class="form-control" placeholder="Add a comment...">
                            <button class="btn btn-outline-primary" type="submit"><i class="bi bi-chat-dots"></i>
                                Comment</button>
                        </div>
                    </form>

                    {{-- Comments --}}
                    @foreach ($post->comments as $comment)
                    <div class="mt-2 border-start ps-2">
                        <strong>{{ $comment->user->name }}:</strong> {{ $comment->content }}
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach

        </div>
    </div>
</div>


{{-- JS for preview --}}
{{-- @push('scripts') --}}
<script>
    function previewImage(event) {
                    const preview = document.getElementById('preview');
                    const previewContainer = document.getElementById('image_preview');
                    const file = event.target.files[0];
                    if (file) {
                        preview.src = URL.createObjectURL(file);
                        previewContainer.classList.remove('d-none');
                    } else {
                        previewContainer.classList.add('d-none');
                        preview.src = '';
                    }
                }
</script>
{{-- @endpush --}}

@endsection
