<?php

namespace Modules\User\Transformers;

use App\Http\Resources\OutputSuccess;

class OutputUserRegister extends OutputSuccess
{
    protected function getMessage()
    {
        return [
            'user' => $this->resource['user']->jsonSerialize(),
            'access' => [
                'access_token' => $this->resource['token'],
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ]
        ];
    }
}
