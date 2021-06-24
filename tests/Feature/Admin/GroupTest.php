<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Group;
use App\Models\User;
use Tests\TestCase;

class GroupTest extends TestCase
{
    public function testsGroupsAreCreatedCorrectly()
    {
        $admin = Admin::factory()->create([
            'name' => 'Alisher',
            'username' => 'test@internations.org',
            'password' => bcrypt('password'),
        ]);

        $user = User::factory()->create();

        $token = $admin->generateToken();

        $headers = ['Authorization' => "Bearer $token"];

        $payload = [
            'name' => 'Ali Baba',
            'users' => [$user->id]
        ];

        $this->json('POST', 'api/v1/admin/groups', $payload, $headers)
            ->assertStatus(201);
    }

    public function testsGroupsAreUpdatedCorrectly()
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
            'users' => [$user->id],
        ];

        $this->json('PUT', 'api/v1/admin/groups/' . $group->id, $payload, $headers)
            ->assertStatus(200);
    }

    public function testsGroupsAreDeletedCorrectly()
    {
        $admin = Admin::factory()->create([
            'name' => 'Alisher',
            'username' => 'test@internations.org',
            'password' => bcrypt('password'),
        ]);

        $group = Group::factory()->create();

        $token = $admin->generateToken();

        $headers = ['Authorization' => "Bearer $token"];

        $this->json('DELETE', 'api/v1/admin/groups/' . $group->id, [], $headers)
            ->assertStatus(200);
    }
}
