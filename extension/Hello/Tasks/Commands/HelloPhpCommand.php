<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Hello\Tasks\Commands;


class HelloPhpCommand extends GreeterCommand
{
    /**
     * @param string $message
     */
    public function __construct(string $message = 'Hello PHP')
    {
        parent::__construct($message);
    }

    /**
     * @return bool
     */
    public function hasStopOnError(): bool
    {
        return true;
    }

    /**
     * @return array<string>
     */
    public function getTags(): array
    {
        return [];
    }
}
