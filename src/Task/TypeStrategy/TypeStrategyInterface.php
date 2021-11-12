<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Task\TypeStrategy;

interface TypeStrategyInterface
{
    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return array
     */
    public function extract(): array;

    /**
     * @param array $definition
     *
     * @return string
     */
    public function execute(array $definition): string;
}
