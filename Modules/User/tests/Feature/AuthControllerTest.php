<?php

namespace Modules\User\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testLoginSuccess(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        $credentials = ['email' => 'test@example.com', 'password' => 'password'];

        $response = $this->postJson('/api/login', $credentials);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'response' => [
                         'access_token',
                         'token_type',
                         'expires_in',
                     ],
                 ]);

        $this->assertAuthenticated();
        $this->assertEquals(Auth::user()->email, 'test@example.com');
    }

    public function testLoginFailure(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        $credentials = ['email' => 'test@example.com', 'password' => 'wrongpassword'];

        $response = $this->postJson('/api/login', $credentials);

        $response->assertStatus(401)
                 ->assertJsonStructure([
                     'response' => [
                        'message'
                     ],
                 ]);

        $this->assertGuest();
    }

    public function testGetAuthenticatedUserSuccess(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        $token = JWTAuth::fromUser($user);

        $response = $this->getJson('/api/me', ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'response' => [
                         'user' => [
                             'id',
                             'name',
                             'email',
                         ],
                     ],
                 ]);

        $this->assertAuthenticatedAs($user);
    }

    public function testGetAuthenticatedUserFailure(): void
    {
        $response = $this->getJson('/api/me');

        $response->assertStatus(401)
                 ->assertJsonStructure([
                     'response' => [
                        'message'
                     ],
                 ]);

        $this->assertGuest();
    }
}
