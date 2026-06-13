<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Models\Client;
use App\Services\ClientService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ClientController extends Controller
{
    public function __construct(
        private readonly ClientService $clientService,
    ) {}

    public function index(): Response
    {
        $search = request('search');

        return Inertia::render('Client/Index', [
            'clients' => $this->clientService->list($search, 10),
            'filters' => $search,
        ]);
    }

    public function store(ClientRequest $request): RedirectResponse
    {
        $this->clientService->store($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Client created.']);

        return back();
    }

    public function update(ClientRequest $request, Client $client): RedirectResponse
    {
        $this->clientService->update($client, $request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Client updated.']);

        return back();
    }

    public function destroy(Client $client): RedirectResponse
    {
        $this->clientService->delete($client);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Client deleted.']);

        return back();
    }
}
