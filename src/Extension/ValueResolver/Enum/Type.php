<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolver\Enum;

final class Type
{
    /**
     * Type for string value.
     *
     * @var string
     */
    public const STRING_TYPE = 'string';

    /**
     * Type for array<string> value.
     *
     * @var string
     */
    public const ARRAY_TYPE = 'array';

    /**
     * Type for path value.
     *
     * @var string
     */
    public const PATH_TYPE = 'path';

    /**
     * Type for bool value.
     *
     * @var string
     */
    public const BOOLEAN_TYPE = 'boolean';
}
