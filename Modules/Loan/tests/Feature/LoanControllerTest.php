<?php

namespace Modules\Loan\Tests\Unit;

use App\Services\Cryptography\JsonWebToken;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Modules\Book\Models\Book;
use Modules\Loan\Models\Loan;
use Modules\User\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoanControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testGetAllLoans(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        $token = JWTAuth::fromUser($admin);

        Loan::factory()->count(3)->create();

        $response = $this->getJson('/api/loan', [
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
    }

    public function testStoreLoan(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        $token = JWTAuth::fromUser($admin);

        $user = User::factory()->create();
        $book = Book::factory()->create();

        $userId = JsonWebToken::encode($user->id);
        $bookId = JsonWebToken::encode($book->id);

        $response = $this->postJson('/api/loan', [
            'user_id' => $userId,
            'book_id' => $bookId
        ], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'success',
                     'error',
                     'response' => [
                         'loan' => [
                             'book',
                             'user',
                         ]
                     ],
                 ]);

        $this->assertDatabaseHas('loans', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'return_date' => null,
        ]);
    }

    public function testShowLoan(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        $token = JWTAuth::fromUser($admin);

        $loan = Loan::factory()->create();
        $loanId = JsonWebToken::encode($loan->id);

        $response = $this->getJson('/api/loan/' . $loanId, [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'error',
                     'response' => [
                         'loan' => [
                             'id',
                             'book',
                             'user',
                         ]
                     ],
                 ]);
        $decodedId = JsonWebToken::decode($response->json('response.loan.id'));
        $this->assertEquals($loan->id, $decodedId);
    }

    public function testReturnLoan()
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        $token = JWTAuth::fromUser($admin);

        $loan = Loan::factory()->create();
        $loanId = JsonWebToken::encode($loan->id);

        $response = $this->putJson('/api/loan/return/' . $loanId, [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'error',
                     'response' => [
                         'loan' => [
                             'book',
                             'user',
                         ]
                     ],
                 ]);

        $decodedId = JsonWebToken::decode($response->json('response.loan.id'));
        $this->assertEquals($loan->id, $decodedId);
        $this->assertDatabaseHas('loans', [
            'id' => $loan->id,
            'return_date' => now()->toDateTime(),
        ]);
    }
}
