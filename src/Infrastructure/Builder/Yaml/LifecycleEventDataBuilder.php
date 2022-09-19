<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\Yaml;

use SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYaml;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\UpdatedEventData;
use SprykerSdk\Sdk\Core\Domain\Enum\LifecycleName;

class LifecycleEventDataBuilder implements LifecycleEventDataBuilderInterface
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\FileCollectionBuilderInterface
     */
    protected FileCollectionBuilderInterface $fileCollectionBuilder;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\LifecycleCommandBuilderInterface
     */
    protected LifecycleCommandBuilderInterface $lifecycleCommandBuilder;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\PlaceholderBuilderInterface
     */
    protected PlaceholderBuilderInterface $placeholderBuilder;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\FileCollectionBuilderInterface $fileCollectionBuilder
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\LifecycleCommandBuilderInterface $lifecycleCommandBuilder
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\PlaceholderBuilderInterface $placeholderBuilder
     */
    public function __construct(
        FileCollectionBuilderInterface $fileCollectionBuilder,
        LifecycleCommandBuilderInterface $lifecycleCommandBuilder,
        PlaceholderBuilderInterface $placeholderBuilder
    ) {
        $this->fileCollectionBuilder = $fileCollectionBuilder;
        $this->lifecycleCommandBuilder = $lifecycleCommandBuilder;
        $this->placeholderBuilder = $placeholderBuilder;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYaml $taskYaml
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEventData
     */
    public function buildInitializedEventData(TaskYaml $taskYaml): InitializedEventData
    {
        $taskData = $taskYaml->getTaskData();
        if (!isset($taskData['lifecycle'][LifecycleName::INITIALIZED])) {
            return new InitializedEventData();
        }

        $taskYamlWithEventData = $taskYaml->withTaskData($taskData['lifecycle'][LifecycleName::INITIALIZED]);

        return new InitializedEventData(
            $this->lifecycleCommandBuilder->buildLifecycleCommands($taskYamlWithEventData),
            $this->placeholderBuilder->buildPlaceholders($taskYamlWithEventData),
            $this->fileCollectionBuilder->buildFiles($taskYamlWithEventData),
        );
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYaml $taskYaml
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEventData
     */
    public function buildRemovedEventData(TaskYaml $taskYaml): RemovedEventData
    {
        $taskData = $taskYaml->getTaskData();
        if (!isset($taskData['lifecycle'][LifecycleName::REMOVED])) {
            return new RemovedEventData();
        }

        $taskYamlWithEventData = $taskYaml->withTaskData($taskData['lifecycle'][LifecycleName::REMOVED]);

        return new RemovedEventData(
            $this->lifecycleCommandBuilder->buildLifecycleCommands($taskYamlWithEventData),
            $this->placeholderBuilder->buildPlaceholders($taskYamlWithEventData),
            $this->fileCollectionBuilder->buildFiles($taskYamlWithEventData),
        );
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYaml $taskYaml
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\UpdatedEventData
     */
    public function buildUpdatedEventData(TaskYaml $taskYaml): UpdatedEventData
    {
        $taskData = $taskYaml->getTaskData();
        if (!isset($taskData['lifecycle'][LifecycleName::UPDATED])) {
            return new UpdatedEventData();
        }

        $taskYamlWithEventData = $taskYaml->withTaskData($taskData['lifecycle'][LifecycleName::UPDATED]);

        return new UpdatedEventData(
            $this->lifecycleCommandBuilder->buildLifecycleCommands($taskYamlWithEventData),
            $this->placeholderBuilder->buildPlaceholders($taskYamlWithEventData),
            $this->fileCollectionBuilder->buildFiles($taskYamlWithEventData),
        );
    }
}
