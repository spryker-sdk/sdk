<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\Controller\v1;

use SprykerSdk\Sdk\Core\Application\Service\ContextFactory;
use SprykerSdk\Sdk\Core\Application\Service\TaskExecutor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TaskRunnerController
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Service\TaskExecutor
     */

    protected TaskExecutor $taskExecutor;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Service\ContextFactory
     */
    protected ContextFactory $contextFactory;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Service\TaskExecutor $taskExecutor
     * @param \SprykerSdk\Sdk\Core\Application\Service\ContextFactory $contextFactory
     */
    public function __construct(TaskExecutor $taskExecutor, ContextFactory $contextFactory)
    {
        $this->taskExecutor = $taskExecutor;
        $this->contextFactory = $contextFactory;
    }

    /**
     * @param string $task
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(string $task): Response
    {
        $context = $this->contextFactory->getContext();
        $context->setFormat('yaml');
        $context = $this->taskExecutor->execute($context, $task);

        $result = [];
        foreach ($context->getMessages() as $message) {
            $result[] = $message->getMessage();
        }

        return new JsonResponse(['result' => $result, 'code' => $context->getExitCode()]);
    }
}
