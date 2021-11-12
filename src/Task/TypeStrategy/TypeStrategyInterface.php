<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Task\TypeStrategy;

use Sdk\Style\StyleInterface;

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
     * @param \Sdk\Style\StyleInterface $style
     *
     * @return string
     */
    public function execute(array $definition, StyleInterface $style): string;
}
