<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\Yaml;

use SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYamlInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\UpdatedEventData;

class LifecycleEventDataBuilder implements LifecycleEventDataBuilderInterface
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\FileBuilderInterface
     */
    protected FileBuilderInterface $fileBuilder;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\LifecycleCommandBuilderInterface
     */
    protected LifecycleCommandBuilderInterface $lifecycleCommandBuilder;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\PlaceholderBuilderInterface
     */
    protected PlaceholderBuilderInterface $placeholderBuilder;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\FileBuilderInterface $fileBuilder
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\LifecycleCommandBuilderInterface $lifecycleCommandBuilder
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\PlaceholderBuilderInterface $placeholderBuilder
     */
    public function __construct(
        FileBuilderInterface $fileBuilder,
        LifecycleCommandBuilderInterface $lifecycleCommandBuilder,
        PlaceholderBuilderInterface $placeholderBuilder
    ) {
        $this->fileBuilder = $fileBuilder;
        $this->lifecycleCommandBuilder = $lifecycleCommandBuilder;
        $this->placeholderBuilder = $placeholderBuilder;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYamlInterface $taskYaml
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEventData
     */
    public function buildInitializedEventData(TaskYamlInterface $taskYaml): InitializedEventData
    {
        $taskData = $taskYaml->getTaskData();
        if (!isset($taskData['lifecycle']['INITIALIZED'])) {
            return new InitializedEventData();
        }

        $eventData = $taskData['lifecycle']['INITIALIZED'];

        return new InitializedEventData(
            $this->lifecycleCommandBuilder->buildLifecycleCommands($taskYaml->withTaskData($eventData)),
            $this->placeholderBuilder->buildPlaceholders($taskYaml->withTaskData($eventData)),
            $this->fileBuilder->buildFiles($taskYaml->withTaskData($eventData)),
        );
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYamlInterface $taskYaml
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEventData
     */
    public function buildRemovedEventData(TaskYamlInterface $taskYaml): RemovedEventData
    {
        $taskData = $taskYaml->getTaskData();
        if (!isset($taskData['lifecycle']['REMOVED'])) {
            return new RemovedEventData();
        }

        $eventData = $taskData['lifecycle']['REMOVED'];

        return new RemovedEventData(
            $this->lifecycleCommandBuilder->buildLifecycleCommands($taskYaml->withTaskData($eventData)),
            $this->placeholderBuilder->buildPlaceholders($taskYaml->withTaskData($eventData)),
            $this->fileBuilder->buildFiles($taskYaml->withTaskData($eventData)),
        );
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYamlInterface $taskYaml
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\UpdatedEventData
     */
    public function buildUpdatedEventData(TaskYamlInterface $taskYaml): UpdatedEventData
    {
        $taskData = $taskYaml->getTaskData();
        if (!isset($taskData['lifecycle']['UPDATED'])) {
            return new UpdatedEventData();
        }

        $eventData = $taskData['lifecycle']['UPDATED'];

        return new UpdatedEventData(
            $this->lifecycleCommandBuilder->buildLifecycleCommands($taskYaml->withTaskData($eventData)),
            $this->placeholderBuilder->buildPlaceholders($taskYaml->withTaskData($eventData)),
            $this->fileBuilder->buildFiles($taskYaml->withTaskData($eventData)),
        );
    }
}
