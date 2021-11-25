<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

interface PlaceholderInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getValueResolver(): string;

    /**
     * @return array
     */
    public function getConfiguration(): array;

    /**
     * @return bool
     */
    public function isOptional(): bool;
}
