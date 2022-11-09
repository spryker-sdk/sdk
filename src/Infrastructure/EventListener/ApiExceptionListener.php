<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\EventListener;

use Psr\Log\LoggerInterface;
use SprykerSdk\Sdk\Infrastructure\Exception\InvalidRequestDataException;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @param bool $isDebug
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(bool $isDebug, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->isDebug = $isDebug;
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
                new JsonResponse(
                    [
                        'message' => $exception->getMessage(),
                    ],
                    Response::HTTP_BAD_REQUEST,
                ),
            );
        }

        $this->logger->error($exception->getMessage());

        $event->setResponse(
            new JsonResponse(
                [
                    'message' => $this->isDebug ? $exception->getMessage() : 'Error',
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR,
            ),
        );
    }
}
