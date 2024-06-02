<?php

namespace Modules\Book\Transformers;

use App\Http\Resources\OutputSuccess;

class OutputBookCollection extends OutputSuccess
{
    protected function getMessage()
    {
        return $this->formatPagination();
    }
}
