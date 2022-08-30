<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\Yaml;

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
     * @param array $taskData
     * @param array $taskListData
     * @param array $tags
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEventData
     */
    public function buildInitializedEventData(array $taskData, array $taskListData, array $tags = []): InitializedEventData
    {
        if (!isset($taskData['lifecycle']['INITIALIZED'])) {
            return new InitializedEventData();
        }

        $eventData = $taskData['lifecycle']['INITIALIZED'];

        return new InitializedEventData(
            $this->lifecycleCommandBuilder->buildLifecycleCommands($eventData),
            $this->placeholderBuilder->buildPlaceholders($eventData, $taskListData, $tags),
            $this->fileBuilder->buildFiles($eventData),
        );
    }

    /**
     * @param array $taskData
     * @param array $taskListData
     * @param array $tags
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEventData
     */
    public function buildRemovedEventData(array $taskData, array $taskListData, array $tags = []): RemovedEventData
    {
        if (!isset($taskData['lifecycle']['REMOVED'])) {
            return new RemovedEventData();
        }

        $eventData = $taskData['lifecycle']['REMOVED'];

        return new RemovedEventData(
            $this->lifecycleCommandBuilder->buildLifecycleCommands($eventData),
            $this->placeholderBuilder->buildPlaceholders($eventData, $taskListData, $tags),
            $this->fileBuilder->buildFiles($eventData),
        );
    }

    /**
     * @param array $taskData
     * @param array $taskListData
     * @param array $tags
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\UpdatedEventData
     */
    public function buildUpdatedEventData(array $taskData, array $taskListData, array $tags = []): UpdatedEventData
    {
        if (!isset($taskData['lifecycle']['UPDATED'])) {
            return new UpdatedEventData();
        }

        $eventData = $taskData['lifecycle']['UPDATED'];

        return new UpdatedEventData(
            $this->lifecycleCommandBuilder->buildLifecycleCommands($eventData),
            $this->placeholderBuilder->buildPlaceholders($eventData, $taskListData, $tags),
            $this->fileBuilder->buildFiles($eventData),
        );
    }
}
