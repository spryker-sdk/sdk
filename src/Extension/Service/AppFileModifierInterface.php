<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Service;

use SprykerSdk\SdkContracts\Entity\ContextInterface;

interface AppFileModifierInterface
{
    /**
     * @param array $content
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return void
     */
    public function write(array $content, ContextInterface $context);

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     * @param string|null $errorMessage
     *
     * @return array
     */
    public function read(ContextInterface $context, ?string $errorMessage = null);

    /**
     * @param callable $replacementFunction
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     * @param string|null $errorMessage
     *
     * @return void
     */
    public function replace(callable $replacementFunction, ContextInterface $context, ?string $errorMessage = null);
}
