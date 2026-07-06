<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $coachA = User::factory()->create([
            'name'  => 'Coach A',
            'email' => 'coach.a@example.com',
            'role'  => 'coach',
        ]);

        $coachB = User::factory()->create([
            'name'  => 'Coach B',
            'email' => 'coach.b@example.com',
            'role'  => 'coach',
        ]);

        $coachAClients = [
            ['name' => 'Alice Martin',   'email' => 'alice.martin@example.com',   'status' => 'active',    'joined_at' => '2023-01-15', 'last_activity_at' => '2024-11-20'],
            ['name' => 'Ben Torres',     'email' => 'ben.torres@example.com',     'status' => 'cancelled', 'joined_at' => '2023-03-08', 'last_activity_at' => '2024-02-10'],
            ['name' => 'Clara Hughes',   'email' => 'clara.hughes@example.com',   'status' => 'past_due',  'joined_at' => '2023-06-22', 'last_activity_at' => '2024-08-05'],
            ['name' => 'David Okafor',   'email' => 'david.okafor@example.com',   'status' => 'active',    'joined_at' => '2024-01-10', 'last_activity_at' => null],
            ['name' => 'Eva Schneider',  'email' => 'eva.schneider@example.com',  'status' => 'active',    'joined_at' => '2024-04-30', 'last_activity_at' => '2024-12-01'],
            ['name' => 'Frank Nguyen',   'email' => 'frank.nguyen@example.com',   'status' => 'cancelled', 'joined_at' => '2022-11-03', 'last_activity_at' => null],
            ['name' => 'Grace Kim',      'email' => 'grace.kim@example.com',      'status' => 'past_due',  'joined_at' => '2024-07-18', 'last_activity_at' => '2024-10-14'],
        ];

        $coachBClients = [
            ['name' => 'Harry Patel',    'email' => 'harry.patel@example.com',    'status' => 'active',    'joined_at' => '2023-02-20', 'last_activity_at' => '2024-09-30'],
            ['name' => 'Isla Reyes',     'email' => 'isla.reyes@example.com',     'status' => 'past_due',  'joined_at' => '2023-09-14', 'last_activity_at' => null],
            ['name' => 'James Cooper',   'email' => 'james.cooper@example.com',   'status' => 'active',    'joined_at' => '2024-02-28', 'last_activity_at' => '2024-11-05'],
            ['name' => 'Karen Walsh',    'email' => 'karen.walsh@example.com',    'status' => 'cancelled', 'joined_at' => '2022-12-01', 'last_activity_at' => '2024-03-22'],
            ['name' => 'Liam Dubois',    'email' => 'liam.dubois@example.com',    'status' => 'active',    'joined_at' => '2024-05-09', 'last_activity_at' => '2024-12-15'],
            ['name' => 'Mia Fernandez',  'email' => 'mia.fernandez@example.com',  'status' => 'past_due',  'joined_at' => '2023-07-27', 'last_activity_at' => null],
        ];

        foreach ($coachAClients as $data) {
            Client::create(array_merge($data, ['coach_id' => $coachA->id]));
        }

        foreach ($coachBClients as $data) {
            Client::create(array_merge($data, ['coach_id' => $coachB->id]));
        }
    }
}
