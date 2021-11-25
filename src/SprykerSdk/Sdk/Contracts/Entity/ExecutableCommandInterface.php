<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Contracts\Entity;

interface ExecutableCommandInterface extends CommandInterface
{
    /**
     * @param array<string, mixed> $resolvedValues
     *
     * @return int
     */
    public function execute(array $resolvedValues): int;
}
