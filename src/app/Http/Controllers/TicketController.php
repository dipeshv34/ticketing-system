<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;

class TicketController extends Controller
{
    /**
     * Display a listing of tickets.
     */
    public function index(Request $request)
    {
        $request->validate([
            'status' => 'in:open,closed,on_hold',
        ]);

        $query = Ticket::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->with('creator', 'assignee')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('tickets.index', compact('tickets'));
    }

    /**
     * Store a newly created ticket.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Create the ticket; created_by = current user automatically
        $ticket = Ticket::create([
            'subject'    => $data['subject'],
            'client_id'  => auth()->user()->client_id,
            'created_by' => auth()->id(),
        ]);

        // And add the initial message as a reply
        $ticket->replies()->create([
            'user_id' => auth()->id(),
            'message' => $data['message'],
        ]);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket created.');
    }

    /**
     * Display the specified ticket with threaded replies.
     */
    public function show(Ticket $ticket)
    {
        // Replies are tenant‑scoped automatically via middleware
        $ticket->load(['creator', 'assignee', 'replies.user']);

        return view('tickets.show', compact('ticket'));
    }

    /**
     * Update ticket status or assignee.
     */
    public function update(Request $request, Ticket $ticket)
    {
        $data = $request->validate([
            'status'      => 'in:open,closed,on_hold',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $ticket->update($data);

        return back()->with('success', 'Ticket updated.');
    }
}
