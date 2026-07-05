<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('returns 401 when unauthenticated accessing client list', function () {
    $this->getJson('/api/clients')->assertUnauthorized();
});

it('returns 401 when unauthenticated accessing single client', function () {
    $this->getJson('/api/clients/1')->assertUnauthorized();
});

it('returns 401 when unauthenticated accessing stats', function () {
    $this->getJson('/api/clients/stats')->assertUnauthorized();
});

it('returns 401 when unauthenticated updating client status', function () {
    $this->patchJson('/api/clients/1/status', ['status' => 'active'])->assertUnauthorized();
});

it('returns 403 when client role accesses client list', function () {
    Sanctum::actingAs(User::factory()->create(['role' => 'client']));

    $this->getJson('/api/clients')->assertForbidden();
});

it('returns 403 when client role accesses stats', function () {
    Sanctum::actingAs(User::factory()->create(['role' => 'client']));

    $this->getJson('/api/clients/stats')->assertForbidden();
});

it('returns 403 when client role accesses single client endpoint', function () {
    Sanctum::actingAs(User::factory()->create(['role' => 'client']));

    $this->getJson('/api/clients/1')->assertForbidden();
});

it('returns 403 when client role attempts status update', function () {
    Sanctum::actingAs(User::factory()->create(['role' => 'client']));

    $this->patchJson('/api/clients/1/status', ['status' => 'active'])->assertForbidden();
});

it('allows coach to access client list', function () {
    Sanctum::actingAs(User::factory()->create(['role' => 'coach']));

    $this->getJson('/api/clients')->assertOk();
});
