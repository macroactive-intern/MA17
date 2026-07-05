<?php

use App\Models\Client;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('coach A list total is 5 not 8 when coach B has 3 clients', function () {
    $coachA = User::factory()->create(['role' => 'coach']);
    $coachB = User::factory()->create(['role' => 'coach']);
    Client::factory()->count(5)->create(['coach_id' => $coachA->id]);
    Client::factory()->count(3)->create(['coach_id' => $coachB->id]);
    Sanctum::actingAs($coachA);

    $this->getJson('/api/clients')
        ->assertJsonPath('total', 5);
});

it('coach A viewing coach B client returns 403 not 404', function () {
    $coachA = User::factory()->create(['role' => 'coach']);
    $coachB = User::factory()->create(['role' => 'coach']);
    $client = Client::factory()->create(['coach_id' => $coachB->id]);
    Sanctum::actingAs($coachA);

    $this->getJson("/api/clients/{$client->id}")
        ->assertStatus(403);
});

it('coach A cannot update coach B client status', function () {
    $coachA = User::factory()->create(['role' => 'coach']);
    $coachB = User::factory()->create(['role' => 'coach']);
    $client = Client::factory()->create(['coach_id' => $coachB->id, 'status' => 'active']);
    Sanctum::actingAs($coachA);

    $this->patchJson("/api/clients/{$client->id}/status", ['status' => 'cancelled'])
        ->assertForbidden();

    expect($client->fresh()->status)->toBe('active');
});

it('coach A list does not include any of coach B clients', function () {
    $coachA = User::factory()->create(['role' => 'coach']);
    $coachB = User::factory()->create(['role' => 'coach']);
    $coachAClients = Client::factory()->count(3)->create(['coach_id' => $coachA->id]);
    Client::factory()->count(5)->create(['coach_id' => $coachB->id]);
    Sanctum::actingAs($coachA);

    $response = $this->getJson('/api/clients')->assertOk();
    $returnedIds = collect($response->json('data'))->pluck('id')->sort()->values()->all();
    $expectedIds = $coachAClients->pluck('id')->sort()->values()->all();

    expect($returnedIds)->toBe($expectedIds);
});
