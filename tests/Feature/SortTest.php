<?php

use App\Models\Client;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('sorts by name ascending', function () {
    $coach = User::factory()->create(['role' => 'coach']);
    Client::factory()->create(['coach_id' => $coach->id, 'name' => 'Zara']);
    Client::factory()->create(['coach_id' => $coach->id, 'name' => 'Alice']);
    Client::factory()->create(['coach_id' => $coach->id, 'name' => 'Mike']);
    Sanctum::actingAs($coach);

    $data = $this->getJson('/api/clients?sort=name&direction=asc')
        ->assertOk()
        ->json('data');

    expect($data[0]['name'])->toBe('Alice');
    expect($data[1]['name'])->toBe('Mike');
    expect($data[2]['name'])->toBe('Zara');
});

it('sorts by name descending', function () {
    $coach = User::factory()->create(['role' => 'coach']);
    Client::factory()->create(['coach_id' => $coach->id, 'name' => 'Zara']);
    Client::factory()->create(['coach_id' => $coach->id, 'name' => 'Alice']);
    Client::factory()->create(['coach_id' => $coach->id, 'name' => 'Mike']);
    Sanctum::actingAs($coach);

    $data = $this->getJson('/api/clients?sort=name&direction=desc')
        ->assertOk()
        ->json('data');

    expect($data[0]['name'])->toBe('Zara');
    expect($data[1]['name'])->toBe('Mike');
    expect($data[2]['name'])->toBe('Alice');
});

it('sorts by joined_at ascending', function () {
    $coach = User::factory()->create(['role' => 'coach']);
    Client::factory()->create(['coach_id' => $coach->id, 'joined_at' => '2024-03-01']);
    Client::factory()->create(['coach_id' => $coach->id, 'joined_at' => '2024-01-01']);
    Client::factory()->create(['coach_id' => $coach->id, 'joined_at' => '2024-02-01']);
    Sanctum::actingAs($coach);

    $data = $this->getJson('/api/clients?sort=joined_at&direction=asc')
        ->assertOk()
        ->json('data');

    expect($data[0]['joined_at'])->toContain('2024-01-01');
    expect($data[1]['joined_at'])->toContain('2024-02-01');
    expect($data[2]['joined_at'])->toContain('2024-03-01');
});

it('sorts by joined_at descending', function () {
    $coach = User::factory()->create(['role' => 'coach']);
    Client::factory()->create(['coach_id' => $coach->id, 'joined_at' => '2024-03-01']);
    Client::factory()->create(['coach_id' => $coach->id, 'joined_at' => '2024-01-01']);
    Client::factory()->create(['coach_id' => $coach->id, 'joined_at' => '2024-02-01']);
    Sanctum::actingAs($coach);

    $data = $this->getJson('/api/clients?sort=joined_at&direction=desc')
        ->assertOk()
        ->json('data');

    expect($data[0]['joined_at'])->toContain('2024-03-01');
    expect($data[1]['joined_at'])->toContain('2024-02-01');
    expect($data[2]['joined_at'])->toContain('2024-01-01');
});

it('sort=engagement sorts by last_activity_at descending', function () {
    $coach = User::factory()->create(['role' => 'coach']);
    Client::factory()->create(['coach_id' => $coach->id, 'name' => 'Recent', 'last_activity_at' => '2024-12-01']);
    Client::factory()->create(['coach_id' => $coach->id, 'name' => 'Older', 'last_activity_at' => '2024-01-01']);
    Client::factory()->create(['coach_id' => $coach->id, 'name' => 'Middle', 'last_activity_at' => '2024-06-01']);
    Sanctum::actingAs($coach);

    $data = $this->getJson('/api/clients?sort=engagement')
        ->assertOk()
        ->json('data');

    expect($data[0]['name'])->toBe('Recent');
    expect($data[1]['name'])->toBe('Middle');
    expect($data[2]['name'])->toBe('Older');
});

it('sort=engagement forces desc even when direction=asc is passed', function () {
    $coach = User::factory()->create(['role' => 'coach']);
    Client::factory()->create(['coach_id' => $coach->id, 'name' => 'Recent', 'last_activity_at' => '2024-12-01']);
    Client::factory()->create(['coach_id' => $coach->id, 'name' => 'Older', 'last_activity_at' => '2024-01-01']);
    Sanctum::actingAs($coach);

    $data = $this->getJson('/api/clients?sort=engagement&direction=asc')
        ->assertOk()
        ->json('data');

    expect($data[0]['name'])->toBe('Recent');
    expect($data[1]['name'])->toBe('Older');
});

it('invalid sort defaults to name ascending', function () {
    $coach = User::factory()->create(['role' => 'coach']);
    Client::factory()->create(['coach_id' => $coach->id, 'name' => 'Zara']);
    Client::factory()->create(['coach_id' => $coach->id, 'name' => 'Alice']);
    Sanctum::actingAs($coach);

    $data = $this->getJson('/api/clients?sort=password')
        ->assertOk()
        ->json('data');

    expect($data[0]['name'])->toBe('Alice');
    expect($data[1]['name'])->toBe('Zara');
});

it('invalid direction defaults to ascending', function () {
    $coach = User::factory()->create(['role' => 'coach']);
    Client::factory()->create(['coach_id' => $coach->id, 'name' => 'Zara']);
    Client::factory()->create(['coach_id' => $coach->id, 'name' => 'Alice']);
    Sanctum::actingAs($coach);

    $data = $this->getJson('/api/clients?sort=name&direction=sideways')
        ->assertOk()
        ->json('data');

    expect($data[0]['name'])->toBe('Alice');
    expect($data[1]['name'])->toBe('Zara');
});
