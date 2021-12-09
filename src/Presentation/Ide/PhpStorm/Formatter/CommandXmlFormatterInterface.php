<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Formatter;

use SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\CommandInterface;

interface CommandXmlFormatterInterface
{
    /**
     * @param \SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\CommandInterface $ideCommand
     *
     * @return array
     */
    public function format(CommandInterface $command): array;
}
