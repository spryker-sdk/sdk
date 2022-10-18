<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Collector;

use SprykerSdk\Sdk\Infrastructure\Dto\ManifestCollectionDto;

interface TaskYamlCollectorInterface
{
    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Dto\ManifestCollectionDto
     */
    public function collectAll(): ManifestCollectionDto;
}
