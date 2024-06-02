<?php

namespace Modules\Author\Transformers;

use App\Http\Resources\OutputSuccess;

class OutputAuthorCollection extends OutputSuccess
{
    protected function getMessage()
    {
        return $this->formatPagination();
    }
}
