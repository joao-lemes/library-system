<?php

namespace App\Exceptions;

use Exception;

class NotFoundException extends BaseException
{
    public function __construct(string $message = null, int $code = 404, Exception $previous = null) {
        if (empty($message)) {
            $message = trans('exception.not_found.default');
        }

        parent::__construct($message, $code, $previous);
    }
}
