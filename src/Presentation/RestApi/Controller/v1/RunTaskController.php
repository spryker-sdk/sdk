<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\Controller\v1;

use SprykerSdk\Sdk\Presentation\RestApi\Enum\OpenApiType;
use SprykerSdk\Sdk\Presentation\RestApi\Processor\RunTaskProcessor;
use Symfony\Component\HttpFoundation\JsonResponse;

class RunTaskController extends BaseController
{
    /**
     * @var \SprykerSdk\Sdk\Presentation\RestApi\Processor\RunTaskProcessor
     */
    protected RunTaskProcessor $runTaskProcessor;

    /**
     * @param \SprykerSdk\Sdk\Presentation\RestApi\Processor\RunTaskProcessor $runTaskProcessor
     */
    public function __construct(RunTaskProcessor $runTaskProcessor)
    {
        $this->runTaskProcessor = $runTaskProcessor;
    }

    /**
     * @param string $task
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function __invoke(string $task): JsonResponse
    {
        $responseData = $this->runTaskProcessor->process($task);

        return $this->createSuccessResponse($task, OpenApiType::RUN_TASK, $responseData);
    }
}
