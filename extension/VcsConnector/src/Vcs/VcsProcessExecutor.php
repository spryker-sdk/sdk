<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace VcsConnector\Vcs;

use Symfony\Component\Process\Process;
use VcsConnector\Exception\AdapterDoesNotExistException;

class VcsProcessExecutor
{
    /**
     * @param string $projectFolder
     * @param array $command
     *
     * @throws \VcsConnector\Exception\AdapterDoesNotExistException
     *
     * @return void
     */
    public function process(string $projectFolder, array $command): void
    {
        $process = new Process($command, $projectFolder);
        $process->setTimeout(0);
        $process->run();

        if ($process->getExitCode() !== 0) {
            throw new AdapterDoesNotExistException($process->getErrorOutput());
        }
    }
}
