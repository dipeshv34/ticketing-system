<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clients;

class ClientController extends Controller
{
    /**
     * Display a listing of clients.
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        $clients = Clients::paginate(20);
        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new client.
     */
    public function create(): \Illuminate\Contracts\View\View
    {
        return view('clients.create');
    }

    /**
     * Store a newly created client.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'   => 'required|string|max:255',
            'active' => 'boolean',
        ]);

        Clients::create($data);

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client created.');
    }

    /**
     * Show the form for editing the specified client.
     */
    public function edit(Clients $client)
    {
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified client.
     */
    public function update(Request $request, Clients $client)
    {
        $data = $request->validate([
            'name'   => 'required|string|max:255',
            'active' => 'boolean',
        ]);

        $client->update($data);

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client updated.');
    }

    /**
     * Remove (deactivate) the specified client.
     */
    public function destroy(Clients $client)
    {
        $client->active = false;
        $client->save();

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client deactivated.');
    }
}
