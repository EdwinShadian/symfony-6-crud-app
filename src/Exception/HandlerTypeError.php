<?php

namespace App\Exception;

class HandlerTypeError extends \RuntimeException
{
    public function __construct(
        string $message = 'Handler for this type does not exist',
        int $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
