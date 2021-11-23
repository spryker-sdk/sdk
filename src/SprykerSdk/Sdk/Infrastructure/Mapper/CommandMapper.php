<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Mapper;


use SprykerSdk\Sdk\Core\Domain\Entity\CommandInterface;
use SprykerSdk\Sdk\Infrastructure\Entity\Command;
use SprykerSdk\Sdk\Infrastructure\Entity\Task;

class CommandMapper implements CommandMapperInterface
{
    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\CommandInterface $command
     * @param \SprykerSdk\Sdk\Infrastructure\Entity\Task|null $task
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Command>
     */
    public function mapCommand(CommandInterface $command): Command
    {
        return new Command(
            $command->getCommand(),
            $command->getType(),
            $command->hasStopOnError()
        );
    }
}
