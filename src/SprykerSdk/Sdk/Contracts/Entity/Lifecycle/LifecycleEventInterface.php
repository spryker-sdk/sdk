<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Contracts\Entity\Lifecycle;

interface LifecycleEventInterface
{
    /**
     * @return \SprykerSdk\Sdk\Contracts\Entity\CommandInterface[]
     */
    public function getCommands(): array;

    /**
     * @return \SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface[]
     */
    public function getPlaceholders(): array;

    /**
     * @return \SprykerSdk\Sdk\Contracts\Entity\FileInterface[]
     */
    public function getFiles(): array;
}
