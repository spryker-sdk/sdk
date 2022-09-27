<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\TaskSet;

use SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYaml;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

interface TaskFromYamlTaskSetBuilderInterface
{
    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYaml $taskYaml
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    public function buildTaskFromYamlTaskSet(TaskYaml $taskYaml): TaskInterface;
}
