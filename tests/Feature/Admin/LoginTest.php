<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function testRequiresUsernameAndLogin()
    {
        $this->json('POST', 'api/v1/auth/admin/login')
            ->assertStatus(422)
            ->assertJson([
                "message" => "The username field is required.",
                "errors" => [
                    "username" => [
                        "The username field is required."
                    ],
                    "password" => [
                        "The password field is required."
                    ]
                ]
            ]);
    }


    public function testAdminLoginsSuccessfully()
    {
        $user = Admin::factory()->create([
            'name' => 'Alisher',
            'username' => 'test@internations.org',
            'password' => bcrypt('password'),
        ]);

        $payload = ['username' => 'test@internations.org', 'password' => 'password'];

        $this->json('POST', 'api/v1/auth/admin/login', $payload)
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'token',
            ]);
    }
}
