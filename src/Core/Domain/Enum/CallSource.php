<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Enum;

final class CallSource
{
    /**
     * @var string
     */
    public const SOURCE_TYPE_REST_API = 'rest';

    /**
     * @var string
     */
    public const SOURCE_TYPE_CLI = 'cli';
}
