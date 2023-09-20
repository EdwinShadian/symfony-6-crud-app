<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class FileTypeException extends HttpException
{
    public function __construct(string $message = 'Invalid file type', int $statusCode = 422)
    {
        parent::__construct($statusCode, $message);
    }
}
