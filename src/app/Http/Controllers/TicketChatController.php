<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketChat;
use Illuminate\Support\Facades\Storage;

class TicketChatController extends Controller
{
    /**
     * Store a new reply on the given ticket.
     */
    public function store(Request $request, Ticket $ticket)
    {
        $data = $request->validate([
            'message'     => 'required|string',
            'attachments' => 'array',
            'attachments.*' => 'file|max:5120',
        ]);

        // Create the reply
        $chat = $ticket->replies()->create([
            'user_id' => auth()->id(),
            'message' => $data['message'],
        ]);

        // Handle optional file attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('ticket_attachments');
                $chat->attachments()->create([
                    'file_path' => $path,
                ]);
            }
        }

        // TODO: Dispatch email notifications to other participants
        // SendTicketReplyNotification::dispatch($reply);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Reply added.');
    }
}
