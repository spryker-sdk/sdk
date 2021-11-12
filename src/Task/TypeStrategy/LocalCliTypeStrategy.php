<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Task\TypeStrategy;

use Sdk\Task\Exception\TaskDefinitionFailed;
use Symfony\Component\Process\Process;

class LocalCliTypeStrategy extends AbstractTypeStrategy
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return 'local_cli';
    }

    /**
     * @return array
     */
    public function extract(): array
    {
        return $this->definition;
    }

    /**
     * @param array $definition
     *
     * @return string
     */
    public function execute(array $definition): string
    {
        $placeholders = $definition['placeholders'];
        $values = [];
        foreach ($placeholders as $placeholder) {
            $values['%' . $placeholder['name'] . '%'] = $placeholder['value'];
        }
        $command = str_replace(array_keys($values), array_values($values), $definition['command']);


        $process = new Process(explode(' ', $command));
        $process->run();

        if (!$process->isSuccessful()) {
            throw new TaskDefinitionFailed($process->getErrorOutput() ?: $process->getOutput());
        }

        return $process->getOutput();
    }
}
