<?php

namespace Modules\Book\Tests\Unit;

use App\Services\Cryptography\JsonWebToken;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Modules\Author\Models\Author;
use Modules\Book\Models\Book;
use Modules\User\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testListBooks(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        $token = JWTAuth::fromUser($admin);

        Book::factory()->count(15)->create();

        $response = $this->getJson('/api/book?page=1', [
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

    public function testStoreBook(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        $token = JWTAuth::fromUser($admin);

        $authors = Author::factory()->count(2)->create();
        $authorIds = $authors->pluck('id')->map(fn($id) => JsonWebToken::encode($id))->toArray();

        $bookData = [
            'title' => 'New Book Title',
            'year_of_publication' => '2023',
            'author_ids' => $authorIds,
        ];

        $response = $this->postJson('/api/book', $bookData, [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'success',
                     'error',
                     'response' => [
                         'book' => [
                             'authors',
                         ],
                     ],
                 ]);

        $this->assertDatabaseHas('books', [
            'title' => 'New Book Title',
            'year_of_publication' => '2023',
        ]);

        $book = Book::where('title', 'New Book Title')->first();

        foreach ($authors as $author) {
            $this->assertDatabaseHas('author_book', [
                'author_id' => $author->id,
                'book_id' => $book->id,
            ]);
        }
    }

    public function testShowBook(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        $token = JWTAuth::fromUser($admin);

        $book = Book::factory()->create();
        $bookId = JsonWebToken::encode($book->id);

        $response = $this->getJson('/api/book/' . $bookId, [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'error',
                     'response' => [
                         'book' => [
                             'authors',
                         ],
                     ],
                 ]);

        $responseBookId = JsonWebToken::decode($response->json('response.book.id'));
        $this->assertEquals($book->id, $responseBookId);
    }

    public function testUpdateBook(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        $token = JWTAuth::fromUser($admin);

        $book = Book::factory()->create();
        $bookId = JsonWebToken::encode($book->id);

        $authors = Author::factory()->count(2)->create();
        $authorIds = $authors->pluck('id')->map(fn($id) => JsonWebToken::encode($id))->toArray();

        $updatedData = [
            'title' => 'Updated Book Title',
            'year_of_publication' => '2022',
            'author_ids' => $authorIds,
        ];

        $response = $this->putJson('/api/book/' . $bookId, $updatedData, [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'error',
                     'response' => [
                         'book' => [
                             'authors',
                         ],
                     ],
                 ]);

        $this->assertDatabaseHas('books', [
            'title' => 'Updated Book Title',
            'year_of_publication' => '2022',
        ]);

        foreach ($authors as $author) {
            $this->assertDatabaseHas('author_book', [
                'author_id' => $author->id,
                'book_id' => $book->id,
            ]);
        }
    }

    public function testDestroyBook(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        $token = JWTAuth::fromUser($admin);

        $book = Book::factory()->create();
        $bookId = JsonWebToken::encode($book->id);

        $response = $this->deleteJson('/api/book/' . $bookId, [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(204);

        $this->assertSoftDeleted('books', [
            'id' => $book->id,
        ]);

        foreach ($book->authors as $author) {
            $this->assertDatabaseMissing('author_book', [
                'author_id' => $author->id,
                'book_id' => $book->id,
            ]);
        }
    }
}
