<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use SprykerSdk\Sdk\Core\Appplication\Dependency\TasksRepositoryInstallerInterface;
use Symfony\Component\Process\Process;

class TasksRepositoryInstaller implements TasksRepositoryInstallerInterface
{
    /**
     * @return array<string>
     */
    public function install(): array
    {
        $this->runProcess('git submodule init');
        $process = $this->runProcess('git submodule');

        $output = $process->getOutput();

        preg_match_all('~(?:\S+) (\S+)~sm', $output, $matches);

        return $this->updateModules($matches[1]);
    }

    /**
     * @param array $modules
     *
     * @return array
     */
    protected function updateModules(array $modules): array
    {
        $installationModules = [];
        foreach ($modules as $module) {
            $process = $this->runProcess(sprintf('git submodule update --init --force --remote %s', $module));

            $installationModules[$module] = $process->isSuccessful();
        }

        return $installationModules;
    }

    /**
     * @param string $command
     *
     * @return \Symfony\Component\Process\Process
     */
    protected function runProcess(string $command): Process
    {
        $process = Process::fromShellCommandline($command);
        $process->run();

        return $process;
    }
}
