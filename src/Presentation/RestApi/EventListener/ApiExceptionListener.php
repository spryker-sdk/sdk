<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\EventListener;

use Psr\Log\LoggerInterface;
use SprykerSdk\Sdk\Infrastructure\Exception\InvalidRequestDataException;
use SprykerSdk\Sdk\Presentation\RestApi\Factory\OpenApiResponseFactory;
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
     * @var \SprykerSdk\Sdk\Presentation\RestApi\Factory\OpenApiResponseFactory
     */
    protected OpenApiResponseFactory $responseFactory;

    /**
     * @param bool $isDebug
     * @param \Psr\Log\LoggerInterface $logger
     * @param \SprykerSdk\Sdk\Presentation\RestApi\Factory\OpenApiResponseFactory $responseFactory
     */
    public function __construct(bool $isDebug, LoggerInterface $logger, OpenApiResponseFactory $responseFactory)
    {
        $this->logger = $logger;
        $this->isDebug = $isDebug;
        $this->responseFactory = $responseFactory;
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
                $this->responseFactory->createErrorResponse(
                    [$exception->getMessage()],
                    Response::HTTP_BAD_REQUEST,
                    (string)Response::HTTP_BAD_REQUEST,
                ),
            );

            return;
        }

        $this->logger->error($exception->getMessage());

        $event->setResponse(
            $this->responseFactory->createErrorResponse(
                $this->isDebug ? [$exception->getMessage()] : ['Error'],
                Response::HTTP_INTERNAL_SERVER_ERROR,
                (string)Response::HTTP_INTERNAL_SERVER_ERROR,
            ),
        );
    }
}
