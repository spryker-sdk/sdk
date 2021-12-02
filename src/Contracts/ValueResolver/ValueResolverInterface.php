<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Contracts\ValueResolver;

use SprykerSdk\Sdk\Core\Domain\Entity\Context;

interface ValueResolverInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @return array<string>
     */
    public function getSettingPaths(): array;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return string|null
     */
    public function getAlias(): ?string;

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Context $context
     * @param array<string, mixed> $settingValues
     * @param bool|false $optional
     *
     * @return mixed
     */
    public function getValue(
        Context $context,
        array $settingValues,
        bool $optional = false
    ): mixed;

    /**
     * @return mixed
     */
    public function getDefaultValue(): mixed;
}
