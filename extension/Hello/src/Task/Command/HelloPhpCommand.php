<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Hello\Task\Command;

class HelloPhpCommand extends GreeterCommand
{
    /**
     * {@inheritDoc}
     *
     * @param string $message
     */
    public function __construct(string $message = 'Hello PHP')
    {
        parent::__construct($message);
    }

    /**
     * {@inheritDoc}
     *
     * @return bool
     */
    public function hasStopOnError(): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @return array<string>
     */
    public function getTags(): array
    {
        return [];
    }
}
