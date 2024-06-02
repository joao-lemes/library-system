<?php

namespace Modules\Loan\Transformers;

use App\Http\Resources\OutputSuccess;

class OutputLoan extends OutputSuccess
{
    protected function getMessage()
    {
        return [
            'loan' => $this->resource?->jsonSerialize()
        ];
    }
}
