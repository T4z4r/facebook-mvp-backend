@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4"><i class="bi bi-chat-dots-fill text-primary"></i> Messages</h2>

    {{-- Message Sending Form --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3"><i class="bi bi-send-fill"></i> Send a New Message</h5>
            <form action="{{ route('messages.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="receiver_id" class="form-label">Receiver User ID</label>
                    <input type="number" name="receiver_id" id="receiver_id" class="form-control" placeholder="Enter user ID" required>
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Message</label>
                    <textarea name="message" id="message" class="form-control" placeholder="Type your message" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-envelope-paper-fill"></i> Send Message
                </button>
            </form>
        </div>
    </div>

    {{-- Conversation List --}}
    <h3 class="mb-3"><i class="bi bi-chat-left-text"></i> Conversations</h3>

    @forelse ($conversations as $userId => $messages)
        <div class="card mb-3 border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-person-circle text-secondary"></i>
                    Conversation with User ID: <span class="fw-semibold">{{ $userId }}</span>
                </h5>
                <hr>
                @foreach ($messages as $message)
                    <div class="mb-2">
                        <strong>{{ $message->sender->name }}:</strong>
                        {{ $message->message }}
                        <span class="badge bg-{{ $message->is_read ? 'success' : 'secondary' }} ms-2">
                            {{ $message->is_read ? 'Read' : 'Unread' }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> You have no conversations yet.
        </div>
    @endforelse
</div>
@endsection
