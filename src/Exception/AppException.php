<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AppException extends Exception
{
    public function __construct(
        string $message = '',
        ?int $code = Response::HTTP_INTERNAL_SERVER_ERROR,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            message: $message,
            code: $code ?? Response::HTTP_INTERNAL_SERVER_ERROR,
            previous: $previous
        );
    }
}
