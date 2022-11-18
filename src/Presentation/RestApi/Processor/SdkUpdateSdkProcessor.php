<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\Processor;

use SprykerSdk\Sdk\Core\Application\Dependency\LifecycleManagerInterface;
use SprykerSdk\Sdk\Infrastructure\Service\Initializer;
use SprykerSdk\Sdk\Presentation\Console\Command\AbstractUpdateCommand;
use SprykerSdk\SdkContracts\Entity\MessageInterface;
use Symfony\Component\HttpFoundation\Request;

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
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function process(Request $request): array
    {
        $this->initializerService->initialize($request->request->all());

        $messages = [];
        if ($request->request->get(AbstractUpdateCommand::OPTION_NO_CHECK) !== null) {
            $messages = $this->lifecycleManager->checkForUpdate();
        }

        if ($request->request->get(AbstractUpdateCommand::OPTION_CHECK_ONLY) !== null) {
            $this->lifecycleManager->update();
        }

        $result = array_map(fn (MessageInterface $message): string => $message->getMessage(), $messages);

        return ['messages' => $result];
    }
}
