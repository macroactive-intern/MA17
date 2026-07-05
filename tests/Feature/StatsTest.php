<?php

use App\Models\Client;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('stats total counts only authenticated coach clients', function () {
    $coach = User::factory()->create(['role' => 'coach']);
    $other = User::factory()->create(['role' => 'coach']);
    Client::factory()->count(4)->create(['coach_id' => $coach->id]);
    Client::factory()->count(3)->create(['coach_id' => $other->id]);
    Sanctum::actingAs($coach);

    $this->getJson('/api/clients/stats')
        ->assertOk()
        ->assertJsonPath('total', 4);
});

it('active count is correct', function () {
    $coach = User::factory()->create(['role' => 'coach']);
    Client::factory()->count(2)->create(['coach_id' => $coach->id, 'status' => 'active']);
    Client::factory()->count(1)->create(['coach_id' => $coach->id, 'status' => 'cancelled']);
    Sanctum::actingAs($coach);

    $this->getJson('/api/clients/stats')
        ->assertJsonPath('active', 2);
});

it('cancelled count is correct', function () {
    $coach = User::factory()->create(['role' => 'coach']);
    Client::factory()->count(1)->create(['coach_id' => $coach->id, 'status' => 'active']);
    Client::factory()->count(3)->create(['coach_id' => $coach->id, 'status' => 'cancelled']);
    Sanctum::actingAs($coach);

    $this->getJson('/api/clients/stats')
        ->assertJsonPath('cancelled', 3);
});

it('past due count is correct', function () {
    $coach = User::factory()->create(['role' => 'coach']);
    Client::factory()->count(1)->create(['coach_id' => $coach->id, 'status' => 'active']);
    Client::factory()->count(2)->create(['coach_id' => $coach->id, 'status' => 'past_due']);
    Sanctum::actingAs($coach);

    $this->getJson('/api/clients/stats')
        ->assertJsonPath('past_due', 2);
});

it('newest joined date reflects only authenticated coach clients', function () {
    $coach = User::factory()->create(['role' => 'coach']);
    $other = User::factory()->create(['role' => 'coach']);
    Client::factory()->create(['coach_id' => $coach->id, 'joined_at' => '2024-06-01']);
    Client::factory()->create(['coach_id' => $coach->id, 'joined_at' => '2024-03-01']);
    Client::factory()->create(['coach_id' => $other->id, 'joined_at' => '2024-12-01']);
    Sanctum::actingAs($coach);

    $newest = $this->getJson('/api/clients/stats')
        ->assertOk()
        ->json('newest_joined_at');

    expect($newest)->toContain('2024-06-01');
});

it('other coach clients do not affect stats counts', function () {
    $coach = User::factory()->create(['role' => 'coach']);
    $other = User::factory()->create(['role' => 'coach']);
    Client::factory()->count(2)->create(['coach_id' => $coach->id, 'status' => 'active']);
    Client::factory()->count(10)->create(['coach_id' => $other->id, 'status' => 'active']);
    Sanctum::actingAs($coach);

    $this->getJson('/api/clients/stats')
        ->assertJsonPath('total', 2)
        ->assertJsonPath('active', 2);
});

it('returns zero counts and null date when coach has no clients', function () {
    $coach = User::factory()->create(['role' => 'coach']);
    Sanctum::actingAs($coach);

    $this->getJson('/api/clients/stats')
        ->assertOk()
        ->assertJson([
            'total' => 0,
            'active' => 0,
            'cancelled' => 0,
            'past_due' => 0,
            'newest_joined_at' => null,
        ]);
});
