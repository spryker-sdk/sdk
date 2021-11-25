<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

interface SettingInterface
{
    /**
     * @var string
     */
    public const STRATEGY_MERGE = 'merge';

    /**
     * @var string
     */
    public const STRATEGY_REPLACE = 'replace';

    /**
     * @return string
     */
    public function getPath(): string;

    /**
     * @return mixed
     */
    public function getValues(): mixed;

    /**
     * @param mixed $values
     *
     * @return void
     */
    public function setValues(mixed $values): void;

    /**
     * @return string
     */
    public function getStrategy(): string;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return bool
     */
    public function isProject(): bool;

    /**
     * @return bool
     */
    public function hasInitialization(): bool;

    /**
     * @return string|null
     */
    public function getInitializationDescription(): ?string;
}
