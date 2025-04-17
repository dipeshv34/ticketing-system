@php
    use App\Models\Ticket;
    $userRole = auth()->user()->role;
@endphp

@if($userRole === 'super_admin')
    {{-- Dashboard metrics for Super Admin --}}
    <div>
        <h1>Dashboard</h1>
        <p>Total Tickets: {{ Ticket::count() }}</p>
        <p>Open: {{ Ticket::where('status', 'open')->count() }}</p>
        <p>Closed: {{ Ticket::where('status', 'closed')->count() }}</p>
    </div>
@endif

{{-- Ticket list --}}
<table>
    <thead>
    <tr>
        <th>Subject</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @foreach($tickets as $ticket)
        <tr>
            <td>{{ $ticket->subject }}</td>
            <td>{{ ucfirst($ticket->status) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

{{ $tickets->links() }}
