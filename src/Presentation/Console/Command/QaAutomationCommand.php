<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Command;

use SprykerSdk\Sdk\Core\Application\Dependency\ContextRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Exception\SettingsNotInitializedException;
use SprykerSdk\Sdk\Core\Application\Service\ContextFactory;
use SprykerSdk\Sdk\Core\Application\Service\ProjectWorkflow;
use SprykerSdk\Sdk\Core\Application\Service\TaskExecutor;
use SprykerSdk\Sdk\Infrastructure\Service\DynamicTaskSetCreator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class QaAutomationCommand extends RunTaskWrapperCommand
{
    /**
     * @var string
     */
    protected const TASKS_SETTING_KEY = 'qa_tasks';

    /**
     * @var string
     */
    protected const COMMAND_NAME = 'sdk:qa:run';

    /**
     * @var string
     */
    protected const DESCRIPTION = 'Run configurable qa tasks.';

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\DynamicTaskSetCreator
     */
    protected DynamicTaskSetCreator $dynamicTaskSetCreator;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Service\TaskExecutor $taskExecutor
     * @param \SprykerSdk\Sdk\Core\Application\Service\ProjectWorkflow $projectWorkflow
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\ContextRepositoryInterface $contextRepository
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface $projectSettingRepository
     * @param \SprykerSdk\Sdk\Core\Application\Service\ContextFactory $contextFactory
     * @param \SprykerSdk\Sdk\Infrastructure\Service\DynamicTaskSetCreator $dynamicTaskSetCreator
     */
    public function __construct(
        TaskExecutor $taskExecutor,
        ProjectWorkflow $projectWorkflow,
        ContextRepositoryInterface $contextRepository,
        ProjectSettingRepositoryInterface $projectSettingRepository,
        ContextFactory $contextFactory,
        DynamicTaskSetCreator $dynamicTaskSetCreator
    ) {
        $this->dynamicTaskSetCreator = $dynamicTaskSetCreator;
        try {
            $taskOptions = $this->dynamicTaskSetCreator->getTaskOptions(static::TASKS_SETTING_KEY);
        } catch (SettingsNotInitializedException $e) {
            $this->setHidden(true);
            $taskOptions = [];
        }

        parent::__construct(
            $taskExecutor,
            $projectWorkflow,
            $contextRepository,
            $projectSettingRepository,
            $contextFactory,
            $taskOptions,
            static::DESCRIPTION,
            static::COMMAND_NAME,
        );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $context = $this->buildContext($input);
        $context->setTask($this->dynamicTaskSetCreator->getTask(static::TASKS_SETTING_KEY));
        $context = $this->taskExecutor->execute($context);
        $this->writeContext($input, $context);
        $this->writeFilteredMessages($output, $context);

        return $context->getExitCode();
    }
}
