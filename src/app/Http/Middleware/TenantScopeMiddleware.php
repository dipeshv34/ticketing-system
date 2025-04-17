<?php

namespace App\Http\Middleware;

use App\Models\TicketChat;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketReply;

class TenantScopeMiddleware
{
    /**
     * Handle the incoming request by applying tenant scopes.
     *
     * - For Admin/Client, any Ticket or TicketReply query
     *   will be automatically filtered to their client_id.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Only apply if logged in and not super_admin
        if ($user && $user->role !== 'super_admin' && $user->client_id) {
            $clientId = $user->client_id;

            // Scope Tickets: WHERE client_id = auth->client_id
            Ticket::addGlobalScope('tenant', function (Builder $builder) use ($clientId) {
                $builder->where('client_id', $clientId);
            });

            // Scope Chat: only allows whose ticket belongs to this client
            TicketChat::addGlobalScope('tenant_chat', function (Builder $builder) use ($clientId) {
                $builder->whereHas('ticket', function (Builder $q) use ($clientId) {
                    $q->where('client_id', $clientId);
                });
            });
        }

        return $next($request);
    }
}
