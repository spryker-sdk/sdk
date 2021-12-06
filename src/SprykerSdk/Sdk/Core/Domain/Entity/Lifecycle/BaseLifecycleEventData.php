<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle;

use SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleEventInterface;

abstract class BaseLifecycleEventData implements LifecycleEventInterface
{
    /**
     * @var array<\SprykerSdk\Sdk\Contracts\Entity\CommandInterface>
     */
    protected array $commands;

    /**
     * @var array<\SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface>
     */
    protected array $placeholders;

    /**
     * @var array<\SprykerSdk\Sdk\Contracts\Entity\FileInterface>
     */
    protected array $files;

    /**
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\CommandInterface> $commands
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface> $placeholders
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\FileInterface> $files
     */
    public function __construct(array $commands = [], array $placeholders = [], array $files = [])
    {
        $this->commands = $commands;
        $this->placeholders = $placeholders;
        $this->files = $files;
    }

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
}
