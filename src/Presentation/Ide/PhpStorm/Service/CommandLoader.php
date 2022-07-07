<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Service;

use SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\Command;

class CommandLoader implements CommandLoaderInterface
{
    /**
     * @var iterable<\Symfony\Component\Console\Command\Command>
     */
    protected iterable $commands;

    /**
     * @param iterable<\Symfony\Component\Console\Command\Command> $commands
     */
    public function __construct(iterable $commands)
    {
        $this->commands = $commands;
    }

    /**
     * @return array<\SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\CommandInterface>
     */
    public function load(): array
    {
        $commands = [];

        foreach ($this->commands as $command) {
            if ($command->isHidden()) {
                continue;
            }
            $commands[] = new Command(
                (string)$command->getName(),
                [],
                [],
                $command->getHelp(),
            );
        }

        return $commands;
    }
}
