<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\Yaml;

use SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYamlInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\Lifecycle;
use SprykerSdk\SdkContracts\Entity\Lifecycle\TaskLifecycleInterface;

class LifecycleBuilder implements LifecycleBuilderInterface
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\LifecycleEventDataBuilderInterface
     */
    protected LifecycleEventDataBuilderInterface $lifecycleEventDataBuilder;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\LifecycleEventDataBuilderInterface $lifecycleEventDataBuilder
     */
    public function __construct(LifecycleEventDataBuilderInterface $lifecycleEventDataBuilder)
    {
        $this->lifecycleEventDataBuilder = $lifecycleEventDataBuilder;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYamlInterface $taskListData
     *
     * @return \SprykerSdk\SdkContracts\Entity\Lifecycle\TaskLifecycleInterface
     */
    public function buildLifecycle(TaskYamlInterface $taskListData): TaskLifecycleInterface
    {
        return new Lifecycle(
            $this->lifecycleEventDataBuilder->buildInitializedEventData($taskListData),
            $this->lifecycleEventDataBuilder->buildUpdatedEventData($taskListData),
            $this->lifecycleEventDataBuilder->buildRemovedEventData($taskListData),
        );
    }
}
