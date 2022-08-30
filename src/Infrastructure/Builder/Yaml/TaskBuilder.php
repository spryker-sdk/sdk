<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\Yaml;

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
     * @param array $taskData
     * @param array $taskListData
     * @param array $tags
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Task
     */
    public function buildTask(array $taskData, array $taskListData, array $tags = []): Task
    {
        $placeholders = $this->placeholderBuilder->buildPlaceholders($taskData, $taskListData, $tags);
        $commands = $this->commandBuilder->buildCommands($taskData, $taskListData, $tags);
        $lifecycle = $this->lifecycleBuilder->buildLifecycle($taskData, $taskListData, $tags);

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
