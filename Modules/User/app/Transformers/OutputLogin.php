<?php

namespace Modules\User\Transformers;

use App\Http\Resources\OutputSuccess;

class OutputLogin extends OutputSuccess
{
    protected function getMessage()
    {
        return [
            'access_token' => $this->resource,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }
}
