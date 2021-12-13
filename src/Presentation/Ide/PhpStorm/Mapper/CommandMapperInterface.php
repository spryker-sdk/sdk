<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Mapper;

use SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\CommandInterface;
use Symfony\Component\Console\Command\Command;

interface CommandMapperInterface
{
    /**
     * @param \Symfony\Component\Console\Command\Command $command
     *
     * @return \SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\CommandInterface
     */
    public function mapToIdeCommand(Command $command): CommandInterface;
}
