<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle;

use SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleEventInterface;

class UpdatedEvent implements LifecycleEventInterface
{
    /**
     * @var array<\SprykerSdk\Sdk\Contracts\Entity\CommandInterface>
     */
    protected array $commands = [];

    /**
     * @var array<\SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface>
     */
    protected array $placeholders = [];

    /**
     * @var array<\SprykerSdk\Sdk\Contracts\Entity\FileInterface>
     */
    protected array $files = [];

    /**
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\CommandInterface>
     */
    public function getCommands(): array
    {
        return $this->commands;
    }

    /**
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface>
     */
    public function getPlaceholders(): array
    {
        return $this->placeholders;
    }

    /**
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\FileInterface>
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * @param array $commands
     *
     * @return $this
     */
    public function setCommands(array $commands)
    {
        $this->commands = $commands;

        return $this;
    }

    /**
     * @param array $placeholders
     *
     * @return $this
     */
    public function setPlaceholders(array $placeholders)
    {
        $this->placeholders = $placeholders;

        return $this;
    }

    /**
     * @param array $files
     *
     * @return $this
     */
    public function setFiles(array $files)
    {
        $this->files = $files;

        return $this;
    }
}
