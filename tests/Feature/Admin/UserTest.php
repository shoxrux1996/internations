<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Group;
use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testsUsersAreCreatedCorrectly()
    {
        $admin = Admin::factory()->create([
            'name' => 'Alisher',
            'username' => 'test@internations.org',
            'password' => bcrypt('password'),
        ]);

        $group = Group::factory()->create();

        $token = $admin->generateToken();

        $headers = ['Authorization' => "Bearer $token"];

        $payload = [
            'name' => 'Ali Baba',
            'group_id' => $group->id,
        ];

        $this->json('POST', 'api/v1/admin/users', $payload, $headers)
            ->assertStatus(201);
    }

    public function testsUsersAreUpdatedCorrectly()
    {
        $admin = Admin::factory()->create([
            'name' => 'Alisher',
            'username' => 'test@internations.org',
            'password' => bcrypt('password'),
        ]);

        $group = Group::factory()->create();
        $user = User::factory()->create();

        $token = $admin->generateToken();

        $headers = ['Authorization' => "Bearer $token"];

        $payload = [
            'name' => 'Ali Baba',
            'group_id' => $group->id,
        ];

        $this->json('PUT', 'api/v1/admin/users/' . $user->id, $payload, $headers)
            ->assertStatus(200);
    }

    public function testsUsersAreDeletedCorrectly()
    {
        $admin = Admin::factory()->create([
            'name' => 'Alisher',
            'username' => 'test@internations.org',
            'password' => bcrypt('password'),
        ]);

        $user = User::factory()->for(
            Group::factory()->create()
        )->create();

        $token = $admin->generateToken();

        $headers = ['Authorization' => "Bearer $token"];

        $this->json('DELETE', 'api/v1/admin/users/' . $user->id, [], $headers)
            ->assertStatus(200);
    }
}
