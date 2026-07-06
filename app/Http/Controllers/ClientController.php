<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $this->ensureCoach($request);
    }

    public function stats(Request $request)
    {
        $this->ensureCoach($request);
    }

    public function show(Request $request, Client $client)
    {
        $this->ensureCoach($request);
        $this->ensureOwnsClient($request, $client);

        return response()->json($client);
    }

    public function updateStatus(Request $request, Client $client)
    {
        $this->ensureCoach($request);
        $this->ensureOwnsClient($request, $client);
    }

    private function ensureCoach(Request $request): void
    {
        if ($request->user()->role !== 'coach') {
            abort(403);
        }
    }

    private function ensureOwnsClient(Request $request, Client $client): void
    {
        if ($client->coach_id !== $request->user()->id) {
            abort(403);
        }
    }
}
