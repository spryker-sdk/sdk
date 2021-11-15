<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Port;

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
     * @param array<string, mixed> $settingValues
     * @return mixed
     */
    public function getValue(array $settingValues): mixed;
}