<?php

namespace App\Exceptions;

use Exception;

class BaseException extends Exception
{
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    public function render()
    {
        $output = [
            'success' => false,
            'error' => true,
            'response' => [
                'message' => $this->getMessage()
            ]
        ];

        $debugOutput = [];
        if (config('app.debug')) {
            $debugOutput = [
                'file' => $this->getFile(),
                'line' => $this->getLine(),
                'trace' => $this->getTrace(),
            ];
            $output['response'] = array_merge($output['response'], $debugOutput);
        }

        return response()->json($output, $this->getCode());
    }
}
