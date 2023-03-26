<?php

declare(strict_types=1);

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class ExceptionListener
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    /**
     * @throws Throwable
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $path = preg_replace('#^(/api/[\w-]+).*#', '$1', (string) $event->getRequest()->getPathInfo());
        $exception = $event->getThrowable();
        $this->logger->debug(__METHOD__, ['path' => $path]);
        match ($path) {
            '/api/objective', '/api/prayer', 'program' => false, default => throw $exception
        };

        $message = sprintf('My Error says: %s with code: %s', $exception->getMessage(), $exception->getCode());

        // Customize your response object to display the exception details
        $response = new JsonResponse();
        $response->setData(['error' => $message]);

        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->add($exception->getHeaders());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // sends the modified response object to the event
        $event->setResponse($response);
    }
}
