<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dependency;

use SprykerSdk\Sdk\Contracts\Entity\ContextInterface;

interface ContextRepositoryInterface
{
    /**
     * @param string $name
     * @param \SprykerSdk\Sdk\Contracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\ContextInterface
     */
    public function saveContext(string $name, ContextInterface $context): ContextInterface;

    /**
     * @param string $name
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\ContextInterface|null
     */
    public function findByName(string $name): ?ContextInterface;

    /**
     * @param string $name
     *
     * @return void
     */
    public function deleteByName(string $name): void;
}
