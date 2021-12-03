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
     * @param \SprykerSdk\Sdk\Contracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\ContextInterface
     */
    public function saveContext(ContextInterface $context): ContextInterface;

    /**
     * @param string $name
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\ContextInterface|null
     */
    public function findByName(string $name): ?ContextInterface;

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\ContextInterface $context
     *
     * @return void
     */
    public function delete(ContextInterface $context): void;
}
