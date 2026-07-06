<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $this->ensureCoach($request);

        $query = Client::query()->where('coach_id', $request->user()->id);

        $this->applySearch($query, $request);
        $this->applyStatusFilter($query, $request);
        $this->applySort($query, $request);

        return $query->paginate(20);
    }

    public function stats(Request $request)
    {
        $this->ensureCoach($request);

        $base = Client::query()->where('coach_id', $request->user()->id);

        return response()->json([
            'total'           => (clone $base)->count(),
            'active'          => (clone $base)->where('status', 'active')->count(),
            'cancelled'       => (clone $base)->where('status', 'cancelled')->count(),
            'past_due'        => (clone $base)->where('status', 'past_due')->count(),
            'newest_joined_at' => (clone $base)->max('joined_at'),
        ]);
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

        $validated = $request->validate([
            'status' => ['required', Rule::in(['active', 'cancelled', 'past_due'])],
        ]);

        $client->update(['status' => $validated['status']]);

        return response()->json($client);
    }

    private function applySearch(Builder $query, Request $request): void
    {
        $search = strtolower(trim($request->query('search', '')));

        if ($search === '') {
            return;
        }

        $escaped = $this->escapeLike($search);

        $query->where(function (Builder $inner) use ($escaped) {
            $inner->whereRaw('LOWER(name) LIKE ?', ["%{$escaped}%"])
                  ->orWhereRaw('LOWER(email) LIKE ?', ["%{$escaped}%"]);
        });
    }

    private function applyStatusFilter(Builder $query, Request $request): void
    {
        $status = $request->query('status', '');

        if (in_array($status, ['active', 'cancelled', 'past_due'], true)) {
            $query->where('status', $status);
        }
    }

    private function applySort(Builder $query, Request $request): void
    {
        $allowedSorts = [
            'name'      => 'name',
            'joined_at' => 'joined_at',
            'engagement' => 'last_activity_at',
        ];

        $sort      = $request->query('sort', 'name');
        $direction = $request->query('direction', 'asc');

        if (! array_key_exists($sort, $allowedSorts)) {
            $sort      = 'name';
            $direction = 'asc';
        }

        if (! in_array($direction, ['asc', 'desc'], true)) {
            $direction = 'asc';
        }

        if ($sort === 'engagement') {
            $query->orderBy('last_activity_at', 'desc');
        } else {
            $query->orderBy($allowedSorts[$sort], $direction);
        }
    }

    private function escapeLike(string $value): string
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $value);
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
