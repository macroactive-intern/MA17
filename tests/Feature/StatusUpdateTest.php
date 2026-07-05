<?php

use App\Models\Client;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('coach can update own client status', function () {
    $coach = User::factory()->create(['role' => 'coach']);
    $client = Client::factory()->create(['coach_id' => $coach->id, 'status' => 'active']);
    Sanctum::actingAs($coach);

    $this->patchJson("/api/clients/{$client->id}/status", ['status' => 'cancelled'])
        ->assertOk()
        ->assertJsonPath('status', 'cancelled');

    expect($client->fresh()->status)->toBe('cancelled');
});

it('coach can update status to past_due', function () {
    $coach = User::factory()->create(['role' => 'coach']);
    $client = Client::factory()->create(['coach_id' => $coach->id, 'status' => 'active']);
    Sanctum::actingAs($coach);

    $this->patchJson("/api/clients/{$client->id}/status", ['status' => 'past_due'])
        ->assertOk()
        ->assertJsonPath('status', 'past_due');
});

it('invalid status update returns 422 validation error', function () {
    $coach = User::factory()->create(['role' => 'coach']);
    $client = Client::factory()->create(['coach_id' => $coach->id, 'status' => 'active']);
    Sanctum::actingAs($coach);

    $this->patchJson("/api/clients/{$client->id}/status", ['status' => 'paused'])
        ->assertUnprocessable();

    expect($client->fresh()->status)->toBe('active');
});

it('missing status field returns 422 validation error', function () {
    $coach = User::factory()->create(['role' => 'coach']);
    $client = Client::factory()->create(['coach_id' => $coach->id, 'status' => 'active']);
    Sanctum::actingAs($coach);

    $this->patchJson("/api/clients/{$client->id}/status", [])
        ->assertUnprocessable();
});

it('cross-coach status update returns 403', function () {
    $coachA = User::factory()->create(['role' => 'coach']);
    $coachB = User::factory()->create(['role' => 'coach']);
    $client = Client::factory()->create(['coach_id' => $coachB->id, 'status' => 'active']);
    Sanctum::actingAs($coachA);

    $this->patchJson("/api/clients/{$client->id}/status", ['status' => 'cancelled'])
        ->assertForbidden();

    expect($client->fresh()->status)->toBe('active');
});
