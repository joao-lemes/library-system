<?php

namespace Modules\Loan\Transformers;

use App\Http\Resources\OutputSuccess;

class OutputLoanCollection extends OutputSuccess
{
    protected function getMessage()
    {
        return $this->formatPagination();
    }
}
