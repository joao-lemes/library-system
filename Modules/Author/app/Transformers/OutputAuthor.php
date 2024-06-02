<?php

namespace Modules\Author\Transformers;

use App\Http\Resources\OutputSuccess;

class OutputAuthor extends OutputSuccess
{
    protected function getMessage()
    {
        return [
            'author' => $this->resource?->jsonSerialize()
        ];
    }
}
