<?php

namespace App\Exceptions;

use Exception;

class BadRequestException extends BaseException
{
    public function __construct(string $message = null, int $code = 400, Exception $previous = null) {
        if (empty($message)) {
            $message = trans('exception.bad_request.default');
        }

        parent::__construct($message, $code, $previous);
    }
}
