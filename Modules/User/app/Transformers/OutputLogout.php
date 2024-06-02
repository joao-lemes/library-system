<?php

namespace Modules\User\Transformers;

use App\Http\Resources\OutputSuccess;

class OutputLogout extends OutputSuccess
{
    protected function getMessage()
    {
        return [
            'logout' => $this->resource
        ];
    }
}
