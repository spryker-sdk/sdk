<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Contracts\Entity;

use SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleInterface;
use SprykerSdk\Sdk\Contracts\Entity\Lifecycle\PersistentLifecycleInterface;

interface TaskInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getShortDescription(): string;

    /**
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\CommandInterface>
     */
    public function getCommands(): array;

    /**
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface>
     */
    public function getPlaceholders(): array;

    /**
     * @return string|null
     */
    public function getHelp(): ?string;

    /**
     * @return string
     */
    public function getVersion(): string;

    /**
     * @return bool
     */
    public function isDeprecated(): bool;

    /**
     * @return string|null
     */
    public function getSuccessor(): ?string;

    /**
     * @return \SprykerSdk\Sdk\Contracts\Entity\Lifecycle\PersistentLifecycleInterface|\SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleInterface
     */
    public function getLifecycle(): LifecycleInterface | PersistentLifecycleInterface;
}
