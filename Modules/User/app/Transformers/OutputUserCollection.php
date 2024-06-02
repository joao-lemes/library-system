<?php

namespace Modules\User\Transformers;

use App\Http\Resources\OutputSuccess;

class OutputUserCollection extends OutputSuccess
{
    protected function getMessage()
    {
        return $this->formatPagination();
    }
}
