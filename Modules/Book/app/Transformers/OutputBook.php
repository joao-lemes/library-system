<?php

namespace Modules\Book\Transformers;

use App\Http\Resources\OutputSuccess;

class OutputBook extends OutputSuccess
{
    protected function getMessage()
    {
        return [
            'book' => $this->resource?->jsonSerialize()
        ];
    }
}
