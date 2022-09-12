<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolver;

use SprykerSdk\Sdk\Core\Application\ValueResolver\AbstractValueResolver;
use SprykerSdk\Sdk\Extension\ValueResolver\Enum\Type;

/**
 * @deprecated Use universal `STATIC` value resolver with configuration instead.
 */
class ModuleValueResolver extends AbstractValueResolver
{
    /**
     * @return string
     */
    public function getId(): string
    {
        return 'MODULE';
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return 'module';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Module name in camelcase format';
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return Type::STRING_TYPE;
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return null;
    }

    /**
     * @param array $settingValues
     * @param array $resolvedValues
     *
     * @return array
     */
    public function getChoiceValues(array $settingValues, array $resolvedValues = []): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRequiredSettingPaths(): array
    {
        return [];
    }

    /**
     * @param array<string, mixed> $settingValues
     *
     * @return mixed
     */
    protected function getValueFromSettings(array $settingValues)
    {
        return null;
    }

    /**
     * @return array<string>
     */
    public function getSettingPaths(): array
    {
        return [];
    }
}
