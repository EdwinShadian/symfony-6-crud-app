<?php

namespace App\EventListener;

use App\Helper\ApiResponseHelper;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'handleException',
        ];
    }

    public function handleException(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();

        $this->logger->error($e->getMessage(), ['exception' => $e]);

        if (!$e instanceof HttpExceptionInterface) {
            return;
        }

        $response = ApiResponseHelper::error(
            $e->getMessage(),
            $e->getStatusCode()
        );

        $event->setResponse($response);
    }
}
