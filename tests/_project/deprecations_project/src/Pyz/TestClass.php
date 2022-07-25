<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz;

class TestClass extends TestDeprecatedClass
{
    /**
     * @return string
     */
    public function testMethod(): string
    {
        return static::deprecatedMethod() . static::DEPRECATED_CONST;
    }
}
