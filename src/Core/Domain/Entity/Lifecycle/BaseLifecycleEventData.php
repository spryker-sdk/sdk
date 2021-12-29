<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle;

use SprykerSdk\SdkContracts\Entity\Lifecycle\LifecycleEventDataInterface;

abstract class BaseLifecycleEventData implements LifecycleEventDataInterface
{
    /**
     * @var array<\SprykerSdk\SdkContracts\Entity\CommandInterface>
     */
    protected array $commands;

    /**
     * @var array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface>
     */
    protected array $placeholders;

    /**
     * @var array<\SprykerSdk\SdkContracts\Entity\FileInterface>
     */
    protected array $files;

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\CommandInterface> $commands
     * @param array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface> $placeholders
     * @param array<\SprykerSdk\SdkContracts\Entity\FileInterface> $files
     */
    public function __construct(array $commands = [], array $placeholders = [], array $files = [])
    {
        $this->commands = $commands;
        $this->placeholders = $placeholders;
        $this->files = $files;
    }

    /**
     * @return array<\SprykerSdk\SdkContracts\Entity\CommandInterface>
     */
    public function getCommands(): array
    {
        return $this->commands;
    }

    /**
     * @return array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface>
     */
    public function getPlaceholders(): array
    {
        return $this->placeholders;
    }

    /**
     * @return array<\SprykerSdk\SdkContracts\Entity\FileInterface>
     */
    public function getFiles(): array
    {
        return $this->files;
    }
}
