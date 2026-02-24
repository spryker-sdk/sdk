<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\EventListener;

use SprykerSdk\Sdk\Presentation\RestApi\Exception\InvalidJsonSchemaException;
use SprykerSdk\Sdk\Presentation\RestApi\Factory\OpenApiResponseFactory;
use SprykerSdk\Sdk\Presentation\RestApi\Validator\Json\JsonSchemaValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class JsonRequestListener
{
    /**
     * @var array<string>
     */
    protected array $contentTypes;

    /**
     * @var \SprykerSdk\Sdk\Presentation\RestApi\Validator\Json\JsonSchemaValidator
     */
    protected JsonSchemaValidator $jsonSchemaValidator;

    /**
     * @var \SprykerSdk\Sdk\Presentation\RestApi\Factory\OpenApiResponseFactory
     */
    protected OpenApiResponseFactory $responseFactory;

    /**
     * @param array<string> $contentTypes
     * @param \SprykerSdk\Sdk\Presentation\RestApi\Validator\Json\JsonSchemaValidator $jsonSchemaValidator
     * @param \SprykerSdk\Sdk\Presentation\RestApi\Factory\OpenApiResponseFactory $responseFactory
     */
    public function __construct(
        array $contentTypes,
        JsonSchemaValidator $jsonSchemaValidator,
        OpenApiResponseFactory $responseFactory
    ) {
        $this->contentTypes = $contentTypes;
        $this->jsonSchemaValidator = $jsonSchemaValidator;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
     *
     * @return void
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();

        if (!$this->isApplicable($request) || !$this->supports($request)) {
            return;
        }

        try {
            $this->jsonSchemaValidator->validate($request);
        } catch (InvalidJsonSchemaException $exception) {
            $errorResponse = $this->responseFactory->createErrorResponse(
                $exception->getDetails(),
                $exception->getCode(),
                $exception->getStatus(),
            );

            $event->setResponse($errorResponse);
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function supports(Request $request): bool
    {
        return in_array($request->getContentType(), $this->contentTypes, true) && $request->getContent();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    public function isApplicable(Request $request): bool
    {
        return $request->getContent() !== ''
            && $request->attributes->has('_rest_api');
    }
}
