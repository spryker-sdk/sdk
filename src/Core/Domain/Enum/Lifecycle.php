<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Enum;

final class Lifecycle
{
    /**
     * Provides initialized lifecycle type.
     *
     * @var string
     */
    public const TYPE_INITIALIZED = 'INITIALIZED';

    /**
     * Provides updated lifecycle type.
     *
     * @var string
     */
    public const TYPE_UPDATED = 'UPDATED';

    /**
     * Provides removed lifecycle type.
     *
     * @var string
     */
    public const TYPE_REMOVED = 'REMOVED';
}
