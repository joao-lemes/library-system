<?php

namespace App\Http\Requests;

use App\Exceptions\ForbiddenException;
use App\Exceptions\UnprocessableContentException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{    
    protected function failedAuthorization(): void
    {
        throw new ForbiddenException();
    }
    
    protected function failedValidation(Validator $validator): void
    {
        throw new UnprocessableContentException($validator);
    }
}
