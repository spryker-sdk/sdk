<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Contracts\Entity;

use SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleInterface;

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
     * @return CommandInterface[]
     */
    public function getCommands(): array;

    /**
     * @return PlaceholderInterface[]
     */
    public function getPlaceholders(): array;

    /**
     * @return string|null
     */
    public function getHelp(): ?string;

    /**
     * @return string|null
     */
    public function getVersion(): ?string;

    /**
     * @return bool
     */
    public function isDeprecated(): bool;

    /**
     * @return string|null
     */
    public function getSuccessor(): ?string;

    /**
     * @return \SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleInterface|null
     */
    public function getLifecycle(): ?LifecycleInterface;
}
