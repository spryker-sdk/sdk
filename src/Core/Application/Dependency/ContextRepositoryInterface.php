<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dependency;

use SprykerSdk\SdkContracts\Entity\ContextInterface;

interface ContextRepositoryInterface
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function saveContext(ContextInterface $context): ContextInterface;

    /**
     * @param string $name
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function findByName(string $name): ContextInterface;

    /**
     * Returns last saved context.
     * Creates new context in case no context exists.
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function getLastSavedContextOrNew(): ContextInterface;

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return void
     */
    public function delete(ContextInterface $context): void;
}
