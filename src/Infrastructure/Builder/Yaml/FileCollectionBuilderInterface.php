<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\Yaml;

use SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYamlInterface;

interface FileCollectionBuilderInterface
{
    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYamlInterface $taskYaml
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\FileInterface>
     */
    public function buildFiles(TaskYamlInterface $taskYaml): array;
}
