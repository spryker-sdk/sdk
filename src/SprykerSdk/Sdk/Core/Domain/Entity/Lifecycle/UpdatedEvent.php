<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle;

use SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleEventInterface;

class UpdatedEvent implements LifecycleEventInterface
{
    /**
     * @var \SprykerSdk\Sdk\Contracts\Entity\CommandInterface[]
     */
    protected array $commands = [];

    /**
     * @var \SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface[]
     */
    protected array $placeholders = [];

    /**
     * @var \SprykerSdk\Sdk\Contracts\Entity\FileInterface[]
     */
    protected array $files = [];

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

    /**
     * @param array $commands
     * @return UpdatedEvent
     */
    public function setCommands(array $commands): UpdatedEvent
    {
        $this->commands = $commands;
        return $this;
    }

    /**
     * @param array $placeholders
     * @return UpdatedEvent
     */
    public function setPlaceholders(array $placeholders): UpdatedEvent
    {
        $this->placeholders = $placeholders;
        return $this;
    }

    /**
     * @param array $files
     * @return UpdatedEvent
     */
    public function setFiles(array $files): UpdatedEvent
    {
        $this->files = $files;
        return $this;
    }
}
