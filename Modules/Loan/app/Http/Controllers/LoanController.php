<?php

namespace Modules\Loan\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Loan\Http\Requests\RegisterLoanRequest;
use Modules\Loan\Http\Requests\ReturnLoanRequest;
use Modules\Loan\Http\Requests\ShowLoanRequest;
use Modules\Loan\Services\LoanService;

class LoanController extends Controller
{
    protected $loanService;

    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }

    public function listAction(): JsonResponse
    {
        $output = $this->loanService->getAllLoans();
        return response()->json($output, JsonResponse::HTTP_OK);
    }

    public function storeAction(RegisterLoanRequest $request): JsonResponse
    {
        $output = $this->loanService->store(
            $request->get('user_id'),
            $request->get('book_id')
        );
        return response()->json($output, JsonResponse::HTTP_CREATED);
    }

    public function showAction(ShowLoanRequest $request): JsonResponse
    {
        $output = $this->loanService->getLoanById($request->id);
        return response()->json($output, JsonResponse::HTTP_OK);
    }

    public function returnLoanAction(ReturnLoanRequest $request): JsonResponse
    {
        $output = $this->loanService->returnLoan($request->id);
        return response()->json($output, JsonResponse::HTTP_OK);
    }
}
