<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Enum;

final class Setting
{
    /**
     * Type for shared settings in repository.
     *
     * @var string
     */
    public const SETTING_TYPE_SHARED = 'shared';

    /**
     * Type for local settings.
     *
     * @var string
     */
    public const SETTING_TYPE_LOCAL = 'local';

    /**
     * Type for sdk settings in repository.
     *
     * @var string
     */
    public const SETTING_TYPE_SDK = 'sdk';
}
