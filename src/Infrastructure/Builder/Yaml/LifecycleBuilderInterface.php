<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\Yaml;

use SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYamlInterface;
use SprykerSdk\SdkContracts\Entity\Lifecycle\TaskLifecycleInterface;

interface LifecycleBuilderInterface
{
    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYamlInterface $taskYaml
     *
     * @return \SprykerSdk\SdkContracts\Entity\Lifecycle\TaskLifecycleInterface
     */
    public function buildLifecycle(TaskYamlInterface $taskYaml): TaskLifecycleInterface;
}
