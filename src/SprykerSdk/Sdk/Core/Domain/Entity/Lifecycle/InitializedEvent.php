<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle;

use SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleEventInterface;

class InitializedEvent implements LifecycleEventInterface
{
    public function __construct(
        protected array $commands,
        protected array $placeholders,
        protected array $files,
    ) {
    }

    /**
     * @return \SprykerSdk\Sdk\Contracts\Entity\CommandInterface[]
     */
    public function getCommands(): array
    {
        return $this->commands;
    }

    /**
     * @return \SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface[]
     */
    public function getPlaceholders(): array
    {
        return $this->placeholders;
    }

    /**
     * @return \SprykerSdk\Sdk\Contracts\Entity\FileInterface[]
     */
    public function getFiles(): array
    {
        return $this->files;
    }
}
