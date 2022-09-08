<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\Yaml;

use SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYamlInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Task;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

class TaskBuilder implements TaskBuilderInterface
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\PlaceholderBuilderInterface
     */
    protected PlaceholderBuilderInterface $placeholderBuilder;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\CommandBuilderInterface
     */
    protected CommandBuilderInterface $commandBuilder;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\LifecycleBuilderInterface
     */
    protected LifecycleBuilderInterface $lifecycleBuilder;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\PlaceholderBuilderInterface $placeholderBuilder
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\CommandBuilderInterface $commandBuilder
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\LifecycleBuilderInterface $lifecycleBuilder
     */
    public function __construct(
        PlaceholderBuilderInterface $placeholderBuilder,
        CommandBuilderInterface $commandBuilder,
        LifecycleBuilderInterface $lifecycleBuilder
    ) {
        $this->placeholderBuilder = $placeholderBuilder;
        $this->commandBuilder = $commandBuilder;
        $this->lifecycleBuilder = $lifecycleBuilder;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYamlInterface $taskYaml
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Task
     */
    public function buildTask(TaskYamlInterface $taskYaml): Task
    {
        $placeholders = $this->placeholderBuilder->buildPlaceholders($taskYaml);
        $commands = $this->commandBuilder->buildCommands($taskYaml);
        $lifecycle = $this->lifecycleBuilder->buildLifecycle($taskYaml);

        $taskData = $taskYaml->getTaskData();

        return new Task(
            $taskData['id'],
            $taskData['short_description'],
            $commands,
            $lifecycle,
            $taskData['version'],
            $placeholders,
            $taskData['help'] ?? null,
            $taskData['successor'] ?? null,
            $taskData['deprecated'] ?? false,
            $taskData['stage'] ?? ContextInterface::DEFAULT_STAGE,
            !empty($taskData['optional']),
            $taskData['stages'] ?? [],
        );
    }
}
