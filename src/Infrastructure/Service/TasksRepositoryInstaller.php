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
     * @var string
     */
    protected string $gitModulesPath;

    /**
     * @param string $gitModulesPath
     */
    public function __construct(string $gitModulesPath)
    {
        $this->gitModulesPath = $gitModulesPath;
    }

    /**
     * @return array<string|int, bool>
     */
    public function install(): array
    {
        $gitModules = parse_ini_file($this->gitModulesPath, true);

        if (!$gitModules) {
            return [];
        }

        $installationModules = [];
        foreach ($gitModules as $processSection => $module) {
            $url = $module['url'];
            $path = $module['path'];
            $branch = $module['branch'] ? sprintf('-b %s', $module['branch']) : '';
            $process = $this->runProcess(sprintf('git submodule add %s --force %s %s && git submodule update --init --force --remote %s', $branch, $url, $path, $path));

            $installationModules[$processSection] = $process->isSuccessful();
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
