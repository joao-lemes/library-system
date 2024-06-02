<?php

namespace Modules\Loan\Services;

use App\Services\Cryptography\JsonWebToken;
use Modules\Loan\Repositories\LoanRepository;
use Modules\Loan\Transformers\OutputLoan;
use Modules\Loan\Transformers\OutputLoanCollection;

class LoanService
{
    protected $loanRepository;

    public function __construct(LoanRepository $loanRepository)
    {
        $this->loanRepository = $loanRepository;
    }

    public function getAllLoans(): OutputLoanCollection
    {
        return new OutputLoanCollection($this->loanRepository->all());
    }

    public function getLoanById(string $id): OutputLoan
    {
        return new OutputLoan(
            $this->loanRepository->find(JsonWebToken::decode($id))
        );
    }

    public function store(string $userId, string $bookId): OutputLoan
    {
        return new OutputLoan($this->loanRepository->create(
            JsonWebToken::decode($userId), 
            JsonWebToken::decode($bookId)
        ));
    }

    public function returnLoan(string $id): OutputLoan
    {
        return new OutputLoan($this->loanRepository->returnLoan(
            JsonWebToken::decode($id)
        ));
    }
}
