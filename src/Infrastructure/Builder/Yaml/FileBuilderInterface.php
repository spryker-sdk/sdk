<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\Yaml;

interface FileBuilderInterface
{
    /**
     * @param array $data
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\FileInterface>
     */
    public function buildFiles(array $data): array;
}
