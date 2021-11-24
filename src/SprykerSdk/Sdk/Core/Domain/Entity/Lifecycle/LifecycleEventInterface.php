<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle;

interface LifecycleEventInterface
{
    /**
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\CommandInterface[]
     */
    public function getCommands(): array;

    /**
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\PlaceholderInterface[]
     */
    public function getPlaceholders(): array;

    /**
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\FileInterface[]
     */
    public function getFiles(): array;
}
