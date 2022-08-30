<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\Yaml;

use SprykerSdk\Sdk\Core\Domain\Entity\Command;

class LifecycleCommandBuilder implements LifecycleCommandBuilderInterface
{
    /**
     * @param array $data
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\CommandInterface>
     */
    public function buildLifecycleCommands(array $data): array
    {
        $commands = [];

        if (!isset($data['commands'])) {
            return $commands;
        }

        foreach ($data['commands'] as $command) {
            $commands[] = new Command(
                $command['command'],
                $command['type'],
                false,
            );
        }

        return $commands;
    }
}
