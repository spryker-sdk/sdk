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

class LifecycleEventDataBuilder
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\FileCollectionBuilder
     */
    protected FileCollectionBuilder $fileCollectionBuilder;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\LifecycleCommandBuilder
     */
    protected LifecycleCommandBuilder $lifecycleCommandBuilder;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\PlaceholderBuilder
     */
    protected PlaceholderBuilder $placeholderBuilder;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\FileCollectionBuilder $fileCollectionBuilder
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\LifecycleCommandBuilder $lifecycleCommandBuilder
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\PlaceholderBuilder $placeholderBuilder
     */
    public function __construct(
        FileCollectionBuilder $fileCollectionBuilder,
        LifecycleCommandBuilder $lifecycleCommandBuilder,
        PlaceholderBuilder $placeholderBuilder
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
