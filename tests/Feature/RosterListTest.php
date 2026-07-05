<?php

use App\Models\Client;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('returns paginated own clients with no query parameters', function () {
    $coach = User::factory()->create(['role' => 'coach']);
    Client::factory()->count(3)->create(['coach_id' => $coach->id]);
    Sanctum::actingAs($coach);

    $this->getJson('/api/clients')
        ->assertOk()
        ->assertJsonCount(3, 'data')
        ->assertJsonPath('total', 3);
});

it('pagination total is scoped to authenticated coach', function () {
    $coach = User::factory()->create(['role' => 'coach']);
    $other = User::factory()->create(['role' => 'coach']);
    Client::factory()->count(5)->create(['coach_id' => $coach->id]);
    Client::factory()->count(3)->create(['coach_id' => $other->id]);
    Sanctum::actingAs($coach);

    $this->getJson('/api/clients')
        ->assertJsonPath('total', 5);
});

it('search filters by client name', function () {
    $coach = User::factory()->create(['role' => 'coach']);
    Client::factory()->create(['coach_id' => $coach->id, 'name' => 'Alice Smith', 'email' => 'alice@example.com']);
    Client::factory()->create(['coach_id' => $coach->id, 'name' => 'Bob Jones', 'email' => 'bob@example.com']);
    Sanctum::actingAs($coach);

    $this->getJson('/api/clients?search=alice')
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Alice Smith');
});

it('search filters by client email', function () {
    $coach = User::factory()->create(['role' => 'coach']);
    Client::factory()->create(['coach_id' => $coach->id, 'name' => 'Person One', 'email' => 'alice@example.com']);
    Client::factory()->create(['coach_id' => $coach->id, 'name' => 'Person Two', 'email' => 'bob@example.com']);
    Sanctum::actingAs($coach);

    $this->getJson('/api/clients?search=alice@')
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.email', 'alice@example.com');
});

it('search is case insensitive', function () {
    $coach = User::factory()->create(['role' => 'coach']);
    Client::factory()->create(['coach_id' => $coach->id, 'name' => 'Alice Smith', 'email' => 'alice@example.com']);
    Sanctum::actingAs($coach);

    $this->getJson('/api/clients?search=ALICE')
        ->assertJsonCount(1, 'data');
});

it('search does not return another coachs matching client', function () {
    $coach = User::factory()->create(['role' => 'coach']);
    $other = User::factory()->create(['role' => 'coach']);
    Client::factory()->create(['coach_id' => $other->id, 'name' => 'Alice Smith', 'email' => 'alice@example.com']);
    Sanctum::actingAs($coach);

    $this->getJson('/api/clients?search=alice')
        ->assertJsonCount(0, 'data');
});

it('search=% does not match all clients', function () {
    $coach = User::factory()->create(['role' => 'coach']);
    Client::factory()->count(3)->create(['coach_id' => $coach->id]);
    Sanctum::actingAs($coach);

    $url = '/api/clients?' . http_build_query(['search' => '%']);

    $this->getJson($url)
        ->assertOk()
        ->assertJsonCount(0, 'data');
});

it('filters by valid status active', function () {
    $coach = User::factory()->create(['role' => 'coach']);
    Client::factory()->create(['coach_id' => $coach->id, 'status' => 'active']);
    Client::factory()->create(['coach_id' => $coach->id, 'status' => 'cancelled']);
    Client::factory()->create(['coach_id' => $coach->id, 'status' => 'past_due']);
    Sanctum::actingAs($coach);

    $this->getJson('/api/clients?status=active')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.status', 'active');
});

it('filters by valid status cancelled', function () {
    $coach = User::factory()->create(['role' => 'coach']);
    Client::factory()->create(['coach_id' => $coach->id, 'status' => 'active']);
    Client::factory()->create(['coach_id' => $coach->id, 'status' => 'cancelled']);
    Sanctum::actingAs($coach);

    $this->getJson('/api/clients?status=cancelled')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.status', 'cancelled');
});

it('ignores invalid status and returns all clients', function () {
    $coach = User::factory()->create(['role' => 'coach']);
    Client::factory()->count(3)->create(['coach_id' => $coach->id]);
    Sanctum::actingAs($coach);

    $this->getJson('/api/clients?status=paused')
        ->assertOk()
        ->assertJsonCount(3, 'data');
});

it('paginates at 20 results per page', function () {
    $coach = User::factory()->create(['role' => 'coach']);
    Client::factory()->count(25)->create(['coach_id' => $coach->id]);
    Sanctum::actingAs($coach);

    $this->getJson('/api/clients')
        ->assertOk()
        ->assertJsonCount(20, 'data')
        ->assertJsonPath('total', 25);
});
