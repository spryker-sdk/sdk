<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Contracts\ValueResolver;

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
     * @param bool|false $optional
     * @param array<string, mixed> $resolvedValues
     *
     * @return mixed
     */
    public function getValue(array $settingValues, bool $optional = false): mixed;

    /**
     * @return mixed
     */
    public function getDefaultValue(): mixed;

    /**
     * @param array $resolvedValues
     *
     * @return array
     */
    public function getChoiceValues(array $resolvedValues = []): array;
}
