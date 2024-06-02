<?php

namespace Modules\Loan\Repositories;

use App\Exceptions\NotFoundException;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Loan\Models\Loan;

class LoanRepository
{
    protected $model;

    public function __construct(Loan $model)
    {
        $this->model = $model;
    }

    public function all(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->paginate($perPage);
    }

    public function find(int $id): ?Loan
    {
        return $this->model->find($id);
    }

    public function create(int $userId, int $bookId): Loan
    {
        return $this->model->create([
            'user_id' => $userId,
            'book_id' => $bookId,
            'loan_date' => Carbon::now()->toDateTime()
        ]);
    }

    public function returnLoan(int $id): Loan
    {
        if (!$loan = $this->model->find($id)) {
            throw new NotFoundException(trans('exception.not_found.loan'));
        }
        $loan->update(['return_date' => Carbon::now()->toDateTime()]);

        return $loan;
    }
}
