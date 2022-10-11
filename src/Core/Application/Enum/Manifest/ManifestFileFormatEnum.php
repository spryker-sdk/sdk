<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Enum\Manifest;

final class ManifestFileFormatEnum
{
    /**
     * @var string
     */
    public const YAML = 'yaml';

    /**
     * @var string
     */
    public const PHP = 'php';

    /**
     * @var array<string>
     */
    public const VALID_VALUES = [self::YAML, self::PHP];

    /**
     * @return array<string>
     */
    public static function getValidValues(): array
    {
        return static::VALID_VALUES;
    }
}
