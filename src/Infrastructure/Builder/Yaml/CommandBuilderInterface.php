<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\Yaml;

interface CommandBuilderInterface
{
    /**
     * @param array $data
     * @param array $taskListData
     * @param array<string> $tags
     *
     * @return array<int, \SprykerSdk\SdkContracts\Entity\CommandInterface>
     */
    public function buildCommands(array $data, array $taskListData, array $tags = []): array;
}
