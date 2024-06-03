<?php

namespace Modules\Author\Tests\Unit;

use App\Services\Cryptography\JsonWebToken;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Modules\Author\Models\Author;
use Modules\User\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthorControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testListAuthors(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        $token = JWTAuth::fromUser($admin);

        Author::factory()->count(15)->create();

        $response = $this->getJson('/api/author?page=1', [
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

    public function testStoreAuthor(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        $token = JWTAuth::fromUser($admin);

        $data = [
            'name' => 'Test Author',
            'birth_date' => '2000-01-01',
        ];

        $response = $this->postJson('/api/author', $data, [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'success',
                     'error',
                     'response' => [
                         'author',
                     ],
                 ]);

        $this->assertDatabaseHas('authors', [
            'name' => 'Test Author',
            'birth_date' => '2000-01-01',
        ]);
    }

    public function testShowAuthor(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        $token = JWTAuth::fromUser($admin);

        $author = Author::factory()->create();

        $response = $this->getJson('/api/author/' . JsonWebToken::encode($author->id), [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'error',
                     'response' => [
                         'author',
                     ],
                 ]);

        $decodedId = JsonWebToken::decode($response->json('response.author.id'));

        $this->assertEquals($author->id, $decodedId);
    }
    
    public function testUpdateAuthor(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        $token = JWTAuth::fromUser($admin);

        $author = Author::factory()->create();

        $data = [
            'name' => 'Updated Author',
            'birth_date' => '1990-01-01',
        ];

        $response = $this->putJson('/api/author/' . JsonWebToken::encode($author->id), $data, [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'error',
                     'response' => [
                         'author',
                     ],
                 ]);

        $this->assertDatabaseHas('authors', [
            'id' => $author->id,
            'name' => 'Updated Author',
            'birth_date' => '1990-01-01',
        ]);
    }

    public function testDestroyAuthor(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        $token = JWTAuth::fromUser($admin);

        $author = Author::factory()->create();

        $response = $this->deleteJson('/api/author/' . JsonWebToken::encode($author->id), [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(204);

        $this->assertSoftDeleted('authors', [
            'id' => $author->id,
        ]);
    }
}
