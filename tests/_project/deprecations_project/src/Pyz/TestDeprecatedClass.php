<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz;

/**
 * @deprecated
 */
class TestDeprecatedClass
{
    /**
     * @deprecated
     * @var string
     */
    public const DEPRECATED_CONST = '';

    /**
     * @deprecated
     * @return string|null
     */
    public static function deprecatedMethod(): ?string
    {
        return null;
    }
}
