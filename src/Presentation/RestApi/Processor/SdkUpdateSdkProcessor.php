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
use SprykerSdk\Sdk\Presentation\RestApi\OpenApi\OpenApiRequest;
use SprykerSdk\SdkContracts\Entity\MessageInterface;

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
    public function __construct(
        Initializer $initializerService,
        LifecycleManagerInterface $lifecycleManager
    ) {
        $this->initializerService = $initializerService;
        $this->lifecycleManager = $lifecycleManager;
    }

    /**
     * @param \SprykerSdk\Sdk\Presentation\RestApi\OpenApi\OpenApiRequest $request
     *
     * @return array
     */
    public function process(OpenApiRequest $request): array
    {
        $this->initializerService->initialize($request->getAttributes());

        $messages = [];
        if (!$request->getAttribute(AbstractUpdateCommand::OPTION_NO_CHECK, false)) {
            try {
                $messages = $this->lifecycleManager->checkForUpdate();
            } catch (SdkVersionNotFoundException $exception) {
            }
        }

        if (!$request->getAttribute(AbstractUpdateCommand::OPTION_CHECK_ONLY, false)) {
            $this->lifecycleManager->update();
        }

        $result = array_map(fn (MessageInterface $message): string => $message->getMessage(), $messages);

        return ['messages' => $result];
    }
}
