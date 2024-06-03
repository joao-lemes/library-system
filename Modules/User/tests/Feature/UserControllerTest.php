<?php

namespace Modules\User\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testListUsers(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        $token = JWTAuth::fromUser($admin);

        User::factory()->count(15)->create();

        $response = $this->getJson('/api/user?page=1', [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'error',
                     'response' => [
                         'current_page',
                         'data',
                         'per_page',
                         'total',
                     ],
                 ]);

        $this->assertCount(15, $response->json('response.data'));
    }

    public function testStoreUser(): void
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->postJson('/api/user', $data);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'success',
                     'error',
                     'response' => [
                         'user',
                         'access',
                     ],
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    }
}
