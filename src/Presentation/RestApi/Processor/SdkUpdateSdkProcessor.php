<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\Processor;

use SprykerSdk\Sdk\Core\Application\Dependency\LifecycleManagerInterface;
use SprykerSdk\Sdk\Infrastructure\Exception\SdkVersionNotFoundException;
use SprykerSdk\Sdk\Infrastructure\Service\Initializer;
use SprykerSdk\Sdk\Presentation\Console\Command\AbstractUpdateCommand;
use SprykerSdk\Sdk\Presentation\RestApi\Enum\OpenApiType;
use SprykerSdk\Sdk\Presentation\RestApi\Factory\ResponseFactory;
use SprykerSdk\SdkContracts\Entity\MessageInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SdkUpdateSdkProcessor
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\Initializer
     */
    protected Initializer $initializerService;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\LifecycleManagerInterface
     */
    protected LifecycleManagerInterface $lifecycleManager;

    /**
     * @var \SprykerSdk\Sdk\Presentation\RestApi\Factory\ResponseFactory
     */
    protected ResponseFactory $responseFactory;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Service\Initializer $initializerService
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\LifecycleManagerInterface $lifecycleManager
     * @param \SprykerSdk\Sdk\Presentation\RestApi\Factory\ResponseFactory $responseFactory
     */
    public function __construct(
        Initializer $initializerService,
        LifecycleManagerInterface $lifecycleManager,
        ResponseFactory $responseFactory
    ) {
        $this->initializerService = $initializerService;
        $this->lifecycleManager = $lifecycleManager;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function process(Request $request): JsonResponse
    {
        $this->initializerService->initialize($request->request->all());

        $messages = [];
        if ($request->request->get(AbstractUpdateCommand::OPTION_NO_CHECK) !== null) {
            try {
                $messages = $this->lifecycleManager->checkForUpdate();
            } catch (SdkVersionNotFoundException $exception) {
                return $this->responseFactory->createErrorResponse(
                    [$exception->getMessage()],
                    Response::HTTP_BAD_REQUEST,
                    (string)Response::HTTP_BAD_REQUEST,
                );
            }
        }

        if ($request->request->get(AbstractUpdateCommand::OPTION_CHECK_ONLY) !== null) {
            $this->lifecycleManager->update();
        }

        $result = array_map(fn (MessageInterface $message): string => $message->getMessage(), $messages);

        return $this->responseFactory->createSuccessResponse(
            OpenApiType::SDK_UPDATE_SDK,
            OpenApiType::SDK_UPDATE_SDK,
            ['messages' => $result],
        );
    }
}
