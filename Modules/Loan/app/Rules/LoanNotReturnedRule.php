<?php

namespace Modules\Loan\Rules;

use App\Rules\BaseRule;
use App\Services\Cryptography\JsonWebToken;
use Modules\Loan\Models\Loan;

class LoanNotReturnedRule extends BaseRule
{
    public function passes($attribute, $value): bool
    {
        $loan = Loan::find(JsonWebToken::decode($value));
        return $loan && is_null($loan->return_date);
    }

    public function message(): string
    {
        return trans('exception.loan_already_been_returned');
    }
}
