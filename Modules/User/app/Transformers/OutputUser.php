<?php

namespace Modules\User\Transformers;

use App\Http\Resources\OutputSuccess;

class OutputUser extends OutputSuccess
{
    protected function getMessage()
    {
        return [
            'user' => $this->resource->jsonSerialize()
        ];
    }
}
