<?php

namespace Modules\Loan\Rules;

use App\Rules\BaseRule;
use App\Services\Cryptography\JsonWebToken;
use Modules\Loan\Models\Loan;

class LoanExistsRule extends BaseRule
{
    public function passes($attribute, $value): bool
    {
        $id = JsonWebToken::decode($value);
        $this->validateId($id);

        return Loan::where('id', $id)->exists();
    }

    public function message(): string
    {
        return trans('exception.not_found.loan');
    }
}
