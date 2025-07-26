@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Messages</h2>
    <form action="{{ route('messages.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <input type="number" name="receiver_id" class="form-control" placeholder="Enter user ID">
            <textarea name="message" class="form-control" placeholder="Type your message"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Send Message</button>
    </form>
    <h3>Conversations</h3>
    @foreach ($conversations as $userId => $messages)
        <div class="card mt-3">
            <div class="card-body">
                <h5>Conversation with User ID: {{ $userId }}</h5>
                @foreach ($messages as $message)
                    <p><strong>{{ $message->sender->name }}:</strong> {{ $message->message }} ({{ $message->is_read ? 'Read' : 'Unread' }})</p>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
@endsection
