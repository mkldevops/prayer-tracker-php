<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Throwable;
use Symfony\Component\HttpFoundation\Response;

class AppException extends Exception
{
    public function __construct(string $message = '', string $code = Response::HTTP_INTERNAL_SERVER_ERROR, Throwable $previous = null)
    {
        parent::__construct($message, $code ?? Response::HTTP_INTERNAL_SERVER_ERROR, $previous);
    }
}
