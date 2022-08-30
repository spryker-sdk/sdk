<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\Yaml;

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
     * @param array $taskData
     * @param array $taskListData
     * @param array $tags
     *
     * @return \SprykerSdk\SdkContracts\Entity\Lifecycle\TaskLifecycleInterface
     */
    public function buildLifecycle(array $taskData, array $taskListData, array $tags = []): TaskLifecycleInterface
    {
        return new Lifecycle(
            $this->lifecycleEventDataBuilder->buildInitializedEventData($taskData, $taskListData, $tags),
            $this->lifecycleEventDataBuilder->buildUpdatedEventData($taskData, $taskListData, $tags),
            $this->lifecycleEventDataBuilder->buildRemovedEventData($taskData, $taskListData, $tags),
        );
    }
}
