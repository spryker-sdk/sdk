<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\EventListener;

use Psr\Log\LoggerInterface;
use SprykerSdk\Sdk\Infrastructure\Exception\InvalidRequestDataException;
use SprykerSdk\Sdk\Presentation\RestApi\Builder\ResponseBuilder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ApiExceptionListener
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @var bool
     */
    protected bool $isDebug;

    /**
     * @var \SprykerSdk\Sdk\Presentation\RestApi\Builder\ResponseBuilder
     */
    protected ResponseBuilder $responseBuilder;

    /**
     * @param bool $isDebug
     * @param \Psr\Log\LoggerInterface $logger
     * @param \SprykerSdk\Sdk\Presentation\RestApi\Builder\ResponseBuilder $responseBuilder
     */
    public function __construct(bool $isDebug, LoggerInterface $logger, ResponseBuilder $responseBuilder)
    {
        $this->logger = $logger;
        $this->isDebug = $isDebug;
        $this->responseBuilder = $responseBuilder;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\ExceptionEvent $event
     *
     * @return void
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof InvalidRequestDataException) {
            $event->setResponse(
                $this->responseBuilder->buildErrorResponse(
                    $exception->getMessage(),
                    Response::HTTP_BAD_REQUEST,
                    (string)Response::HTTP_BAD_REQUEST,
                ),
            );
        }

        $this->logger->error($exception->getMessage());

        $event->setResponse(
            $this->responseBuilder->buildErrorResponse(
                $this->isDebug ? $exception->getMessage() : 'Error',
                Response::HTTP_BAD_REQUEST,
                (string)Response::HTTP_BAD_REQUEST,
            ),
        );
    }
}
