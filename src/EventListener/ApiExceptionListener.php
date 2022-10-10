<?php

namespace App\EventListener;

use App\Model\ErrorResponse;
use App\Service\ExceptionHandler\ExceptionMapping;
use App\Service\ExceptionHandler\ExceptionMappingResolver;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ApiExceptionListener
{
    public function __construct(
        private readonly ExceptionMappingResolver $resolver,
        private readonly LoggerInterface $logger
    ) {
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();
        $mapping   = $this->resolver->resolve(get_class($throwable));
        if ($mapping === null) {
            $mapping = ExceptionMapping::fromCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if ($mapping->getCode() >= Response::HTTP_INTERNAL_SERVER_ERROR
            or $mapping->isLoggable()
        ) {
            $this->logger->error($throwable->getMessage(), [
                'trace'    => $throwable->getTraceAsString(),
                'previous' => null !== $throwable->getPrevious()
                    ? $throwable->getPrevious()->getMessage() : '',
            ]);
        }

        $message  = $mapping->isHidden()
            ? Response::$statusTexts[$mapping->getCode()]
            : $throwable->getMessage();
        $data     = (new ErrorResponse($message))->serialize();
        $response = new JsonResponse($data, $mapping->getCode(), [], false);

        $event->setResponse($response);
    }
}