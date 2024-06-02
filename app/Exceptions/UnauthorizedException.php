<?php

namespace App\Exceptions;

use Exception;

class UnauthorizedException extends BaseException
{
    public function __construct(string $message = null, int $code = 401, Exception $previous = null) {
        if (empty($message)) {
            $message = trans('exception.unauthorized');
        }

        parent::__construct($message, $code, $previous);
    }
}
