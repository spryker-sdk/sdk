<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Enum;

final class CommandType
{
    /**
     * @var string
     */
    public const LOCAL_CLI_TYPE = 'local_cli';

    /**
     * @var string
     */
    public const LOCAL_CLI_INTERACTIVE = 'local_cli_interactive';

    /**
     * @var array<string>
     */
    public const LOCAL_CLI_TYPES = [
        self::LOCAL_CLI_TYPE,
        self::LOCAL_CLI_INTERACTIVE,
    ];
}
