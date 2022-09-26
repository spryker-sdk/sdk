<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\Yaml;

use SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYaml;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\Lifecycle;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\TaskLifecycleInterface;

class LifecycleBuilder
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\LifecycleEventDataBuilder
     */
    protected LifecycleEventDataBuilder $lifecycleEventDataBuilder;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\LifecycleEventDataBuilder $lifecycleEventDataBuilder
     */
    public function __construct(LifecycleEventDataBuilder $lifecycleEventDataBuilder)
    {
        $this->lifecycleEventDataBuilder = $lifecycleEventDataBuilder;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYaml $taskYaml
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\TaskLifecycleInterface
     */
    public function buildLifecycle(TaskYaml $taskYaml): TaskLifecycleInterface
    {
        return new Lifecycle(
            $this->lifecycleEventDataBuilder->buildInitializedEventData($taskYaml),
            $this->lifecycleEventDataBuilder->buildUpdatedEventData($taskYaml),
            $this->lifecycleEventDataBuilder->buildRemovedEventData($taskYaml),
        );
    }
}
