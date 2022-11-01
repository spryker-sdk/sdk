<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\Processor;

use SprykerSdk\Sdk\Core\Application\Service\ContextFactory;
use SprykerSdk\Sdk\Core\Application\Service\TaskExecutor;
use SprykerSdk\Sdk\Infrastructure\Mapper\ViolationReportFileMapperInterface;
use SprykerSdk\SdkContracts\Report\Violation\ViolationReportInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\Exception\InvalidResourceException;

class RunTaskProcessor
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
     * @var \SprykerSdk\Sdk\Infrastructure\Mapper\ViolationReportFileMapperInterface
     */
    protected ViolationReportFileMapperInterface $violationReportFileMapperInterface;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Service\TaskExecutor $taskExecutor
     * @param \SprykerSdk\Sdk\Core\Application\Service\ContextFactory $contextFactory
     * @param \SprykerSdk\Sdk\Infrastructure\Mapper\ViolationReportFileMapperInterface $violationReportFileMapperInterface
     */
    public function __construct(
        TaskExecutor $taskExecutor,
        ContextFactory $contextFactory,
        ViolationReportFileMapperInterface $violationReportFileMapperInterface
    ) {
        $this->taskExecutor = $taskExecutor;
        $this->contextFactory = $contextFactory;
        $this->violationReportFileMapperInterface = $violationReportFileMapperInterface;
    }

    /**
     * @param string $task
     *
     * @throws \Symfony\Component\Translation\Exception\InvalidResourceException
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function process(string $task): Response
    {
        $context = $this->taskExecutor->execute($this->contextFactory->getContext(), $task);

        $response = [];
        foreach ($context->getMessages() as $message) {
            $response['messages'][] = $message->getMessage();
        }

        foreach ($context->getReports() as $report) {
            if (!($report instanceof ViolationReportInterface)) {
                throw new InvalidResourceException(sprintf('Invalid report type "%s"', get_class($report)));
            }
            $response['reports'][] = $this->violationReportFileMapperInterface->mapViolationReportToYamlStructure($report);
        }

        return new JsonResponse($response);
    }
}
