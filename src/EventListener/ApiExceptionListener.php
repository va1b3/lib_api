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
            $code    = $throwable->getCode() > 0 ? $throwable->getCode()
                : Response::HTTP_INTERNAL_SERVER_ERROR;
            $mapping = ExceptionMapping::fromCode($code);
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

        $message = $mapping->isHidden()
            ? Response::$statusTexts[$mapping->getCode()]
            : $throwable->getMessage();
        $data    = (new ErrorResponse($mapping->getCode(),
            $message))->serialize();

        $event->setResponse(new JsonResponse($data, $mapping->getCode(), [],
            false));
    }
}