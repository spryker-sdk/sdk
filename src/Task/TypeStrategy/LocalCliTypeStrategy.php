<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Task\TypeStrategy;

use Sdk\Style\StyleInterface;
use Sdk\Task\Exception\TaskExecutionFailed;
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
     * @param \Sdk\Style\StyleInterface $style
     *
     * @return string
     */
    public function execute(array $definition, StyleInterface $style): string
    {
        $placeholders = $definition['placeholders'];
        $values = [];
        foreach ($placeholders as $placeholder) {
            $values[$placeholder['name']] = $placeholder['value'];
            if ($placeholder['type'] === 'bool' && $placeholder['value']) {
                $values[$placeholder['name']] = sprintf('--%s', $placeholder['parameterName']);
            }
        }
        $command = str_replace(array_keys($values), array_values($values), $definition['command']);

        $process = new Process(explode(' ', $command));
        $process->setTimeout(null);
        $process->setIdleTimeout(null);

        $process->run();

        if ($process->isSuccessful()) {
            return $process->getOutput();
        }

        throw new TaskExecutionFailed(!empty($process->getErrorOutput()) ? $process->getErrorOutput() : $process->getOutput());
    }
}
