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
     * @param \SprykerSdk\Sdk\Infrastructure\Service\Initializer $initializerService
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\LifecycleManagerInterface $lifecycleManager
     */
    public function __construct(Initializer $initializerService, LifecycleManagerInterface $lifecycleManager)
    {
        $this->initializerService = $initializerService;
        $this->lifecycleManager = $lifecycleManager;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function process(Request $request): Response
    {
        $this->initializerService->initialize([]);

        $messages = [];
        if ($request->request->get(AbstractUpdateCommand::OPTION_NO_CHECK) !== null) {
            try {
                $messages = $this->lifecycleManager->checkForUpdate();
            } catch (SdkVersionNotFoundException $exception) {
                return new JsonResponse(['result' => 'FAILED', 'messages' => [$exception->getMessage()], 'code' => 400]);
            }
        }

        if ($request->request->get(AbstractUpdateCommand::OPTION_CHECK_ONLY) !== null) {
            $this->lifecycleManager->update();
        }

        $result = [];
        foreach ($messages as $message) {
            $result[] = $message->getMessage();
        }

        return new JsonResponse(['messages' => $result]);
    }
}
