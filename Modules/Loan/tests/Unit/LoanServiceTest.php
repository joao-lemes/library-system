<?php

namespace Modules\Loan\Tests\Unit;

use App\Services\Cryptography\JsonWebToken;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Queue;
use Mockery;
use Modules\Book\Models\Book;
use Modules\Loan\Jobs\SendLoanNotificationJob;
use Modules\Loan\Models\Loan;
use Modules\Loan\Repositories\LoanRepository;
use Modules\Loan\Services\LoanService;
use Modules\Loan\Transformers\OutputLoan;
use Modules\Loan\Transformers\OutputLoanCollection;
use Modules\User\Models\User;

class LoanServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $loanRepository;
    protected LoanService $loanService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->loanRepository = Mockery::mock(LoanRepository::class);
        $this->loanService = new LoanService($this->loanRepository);
    }

    public function testGetAllLoans(): void
    {
        $loans = Loan::factory()->count(3)->make();
        $paginator = new LengthAwarePaginator($loans, 3, 15);
        $this->loanRepository->shouldReceive('all')->andReturn($paginator);

        $result = $this->loanService->getAllLoans();

        $this->assertInstanceOf(OutputLoanCollection::class, $result);
    }

    public function testGetLoanById(): void
    {
        $loan = Loan::factory()->create();
        $loanId = JsonWebToken::encode($loan->id);
        $decodedId = JsonWebToken::decode($loanId);
        $this->loanRepository->shouldReceive('find')->with($decodedId)->andReturn($loan);

        $result = $this->loanService->getLoanById($loanId);

        $this->assertInstanceOf(OutputLoan::class, $result);
    }

    public function testStore(): void
    {
        Queue::fake();

        $user = User::factory()->create();
        $book = Book::factory()->create();

        $loan = Loan::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);

        $userId = JsonWebToken::encode($user->id);
        $bookId = JsonWebToken::encode($book->id);

        $this->loanRepository->shouldReceive('create')
            ->with(JsonWebToken::decode($userId), JsonWebToken::decode($bookId))
            ->andReturn($loan);

        $result = $this->loanService->store($userId, $bookId);

        $this->assertInstanceOf(OutputLoan::class, $result);
    }

    public function testReturnLoan(): void
    {
        $loan = Loan::factory()->create(['return_date' => now()]);
        $loanId = JsonWebToken::encode($loan->id);
        $decodedId = JsonWebToken::decode($loanId);
        $this->loanRepository->shouldReceive('returnLoan')->with($decodedId)->andReturn($loan);

        $result = $this->loanService->returnLoan($loanId);

        $this->assertInstanceOf(OutputLoan::class, $result);
    }
}
