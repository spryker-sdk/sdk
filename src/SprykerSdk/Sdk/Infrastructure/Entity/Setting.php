<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Entity;

use SprykerSdk\Sdk\Core\Domain\Entity\Setting as DomainSetting;

class Setting extends DomainSetting
{
    /**
     * @param int $id
     * @param string $path
     * @param mixed $values
     * @param string $strategy
     *
     * @param bool $hasInitialization
     * @param string|null $initializationDescription
     */
    public function __construct(
        public ?int $id,
        string $path,
        mixed $values,
        string $strategy,
        bool $isProject = true,
        bool $hasInitialization = false,
        ?string $initializationDescription = null
    ) {
        parent::__construct($path, $values, $strategy, $isProject, $hasInitialization, $initializationDescription);
    }
}