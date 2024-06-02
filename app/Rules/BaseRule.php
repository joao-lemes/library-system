<?php

namespace App\Rules;

use App\Exceptions\BadRequestException;
use Illuminate\Contracts\Validation\Rule;

class BaseRule implements Rule
{
    public function __construct()
    {        
    }

    public function passes($attribute, $value): bool
    {
        return true;
    }

    public function message(): string
    {
        return trans('exception.internal_server_error');
    }

    protected function validateId(mixed $id, bool $throwable = true): bool
    {
        $validation = is_numeric($id) && $id > 0;
        if ((!$validation) && $throwable) {
            throw new BadRequestException(trans('exception.bad_request.invalid_id'));
        }

        return $validation;
    }
}
