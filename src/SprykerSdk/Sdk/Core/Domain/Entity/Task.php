<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

class Task
{
    /**
     * @param string $id
     * @param string $shortDescription
     * @param array<\SprykerSdk\Sdk\Core\Domain\Entity\Command> $commands
     * @param array<\SprykerSdk\Sdk\Core\Domain\Entity\Placeholder> $placeholders
     * @param string|null $help
     */
    public function __construct(
        public string $id,
        public string $shortDescription,
        public array $commands,
        public array $placeholders = [],
        public ?string $help = null
    ) {
    }
}