<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Task\Dumper;

interface DefinitionDumperInterface
{
    /**
     * @param int|null $level
     *
     * @return array
     */
    public function dump(?int $level = null): array;
}
