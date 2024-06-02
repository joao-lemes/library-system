<?php

namespace App\Exceptions;

use Exception;

class ForbiddenException extends BaseException
{
    public function __construct(string $message = null, int $code = 403, Exception $previous = null) {
        if (empty($message)) {
            $message = trans('exception.unauthorized');
        }

        parent::__construct($message, $code, $previous);
    }
}
