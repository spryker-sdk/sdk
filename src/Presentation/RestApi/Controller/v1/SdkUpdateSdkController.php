<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\Controller\v1;

use SprykerSdk\Sdk\Infrastructure\Exception\SdkVersionNotFoundException;
use SprykerSdk\Sdk\Presentation\Console\Command\UpdateSdkCommand;
use SprykerSdk\Sdk\Presentation\RestApi\Controller\CommandControllerInterface;
use SprykerSdk\Sdk\Presentation\RestApi\Processor\SdkUpdateSdkProcessor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SdkUpdateSdkController extends BaseController implements CommandControllerInterface
{
    /**
     * @var string
     */
    public const TYPE = 'sdk-update-sdk';

    /**
     * @var \SprykerSdk\Sdk\Presentation\RestApi\Processor\SdkUpdateSdkProcessor
     */
    protected SdkUpdateSdkProcessor $sdkUpdateSdkProcessor;

    /**
     * @param \SprykerSdk\Sdk\Presentation\RestApi\Processor\SdkUpdateSdkProcessor $sdkUpdateSdkProcessor
     */
    public function __construct(SdkUpdateSdkProcessor $sdkUpdateSdkProcessor)
    {
        $this->sdkUpdateSdkProcessor = $sdkUpdateSdkProcessor;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $attributes = $this->sdkUpdateSdkProcessor->process($this->createOpenApiRequest($request));
        } catch (SdkVersionNotFoundException $exception) {
            return $this->responseFactory->createErrorResponse(
                [$exception->getMessage()],
                Response::HTTP_BAD_REQUEST,
                (string)Response::HTTP_BAD_REQUEST,
            );
        }

        return $this->createSuccessResponse(static::TYPE, static::TYPE, $attributes);
    }

    /**
     * @return string
     */
    public function getCommandName(): string
    {
        return UpdateSdkCommand::NAME;
    }
}
