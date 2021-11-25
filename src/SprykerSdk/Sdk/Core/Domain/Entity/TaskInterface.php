<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

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
     * @return array<\SprykerSdk\Sdk\Core\Domain\Entity\CommandInterface>
     */
    public function getCommands(): array;

    /**
     * @return array<\SprykerSdk\Sdk\Core\Domain\Entity\PlaceholderInterface>
     */
    public function getPlaceholders(): array;

    /**
     * @return string|null
     */
    public function getHelp(): ?string;
}
