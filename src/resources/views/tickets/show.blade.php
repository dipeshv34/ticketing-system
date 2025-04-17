<h1>{{ $ticket->subject }}</h1>

<div>
    <h2>Replies</h2>
    @foreach($ticket->replies as $reply)
        <p>{{ $reply->message }}</p>
    @endforeach
</div>

{{-- Reply form --}}
<form action="{{ route('tickets.replies.store', $ticket) }}"
      method="POST"
      enctype="multipart/form-data">
    @csrf

    <div>
        <label for="message">Your Reply:</label><br>
        <textarea name="message" id="message" rows="4" required></textarea>
    </div>

    <div>
        <label for="attachments">Attachments:</label>
        <input type="file" name="attachments[]" multiple>
    </div>

    <button type="submit">Submit Reply</button>
</form>
