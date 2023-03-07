<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\Processor;

use SprykerSdk\Sdk\Core\Application\Dependency\ContextFactoryInterface;
use SprykerSdk\Sdk\Core\Application\Service\TaskExecutor;
use SprykerSdk\Sdk\Infrastructure\Mapper\ViolationReportFileMapperInterface;
use SprykerSdk\SdkContracts\Report\Violation\ViolationReportInterface;

class RunTaskProcessor
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Service\TaskExecutor
     */
    protected TaskExecutor $taskExecutor;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\ContextFactoryInterface
     */
    protected ContextFactoryInterface $contextFactory;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Mapper\ViolationReportFileMapperInterface
     */
    protected ViolationReportFileMapperInterface $violationReportFileMapperInterface;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Service\TaskExecutor $taskExecutor
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\ContextFactoryInterface $contextFactory
     * @param \SprykerSdk\Sdk\Infrastructure\Mapper\ViolationReportFileMapperInterface $violationReportFileMapperInterface
     */
    public function __construct(
        TaskExecutor $taskExecutor,
        ContextFactoryInterface $contextFactory,
        ViolationReportFileMapperInterface $violationReportFileMapperInterface
    ) {
        $this->taskExecutor = $taskExecutor;
        $this->contextFactory = $contextFactory;
        $this->violationReportFileMapperInterface = $violationReportFileMapperInterface;
    }

    /**
     * @param string $task
     *
     * @return array
     */
    public function process(string $task): array
    {
        $context = $this->taskExecutor->execute($this->contextFactory->getContext(), $task);

        $response = [];
        foreach ($context->getMessages() as $message) {
            $response['messages'][] = $message->getMessage();
        }

        foreach ($context->getReports() as $report) {
            if (!$report instanceof ViolationReportInterface) {
                continue;
            }
            $response['reports'][] = $this->violationReportFileMapperInterface
                ->mapViolationReportToYamlStructure($report);
        }

        return $response;
    }
}
