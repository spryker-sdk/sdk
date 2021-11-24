<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Tasks;

use SprykerSdk\Sdk\Core\Domain\Entity\ExecutableCommandInterface;

class HelloPhpCommand implements ExecutableCommandInterface
{
    /**
     * @return string
     */
    public function getCommand(): string
    {
        return '';
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'php';
    }

    /**
     * @return bool
     */
    public function hasStopOnError(): bool
    {
        return true;
    }

    /**
     * @param array $resolvedValues
     *
     * @return int
     */
    public function execute(array $resolvedValues): int
    {
        echo "Hello PHP";

        return 0;
    }

    /**
     * @return string[]
     */
    public function getTags(): array
    {
        return [];
    }
}
