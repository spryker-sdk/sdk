<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Contracts\Entity;

interface ConverterInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return array<string, mixed>
     */
    public function getConfiguration(): array;
}
