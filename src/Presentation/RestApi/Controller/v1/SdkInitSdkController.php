<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\Controller\v1;

use SprykerSdk\Sdk\Infrastructure\Service\Initializer;
use SprykerSdk\Sdk\Presentation\Console\Command\InitSdkCommand;
use SprykerSdk\Sdk\Presentation\RestApi\Controller\CommandControllerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SdkInitSdkController extends BaseController implements CommandControllerInterface
{
    /**
     * @var string
     */
    public const TYPE = 'sdk-init-sdk';

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\Initializer
     */
    protected Initializer $initializerService;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Service\Initializer $initializerService
     */
    public function __construct(Initializer $initializerService)
    {
        $this->initializerService = $initializerService;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $this->initializerService->initialize($this->createOpenApiRequest($request)->getAttributes());

        return $this->createSuccessResponse(static::TYPE, static::TYPE);
    }

    /**
     * @return string
     */
    public function getCommandName(): string
    {
        return InitSdkCommand::NAME;
    }
}
