<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use Psr\Log\LoggerInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\TasksRepositoryInstallerInterface;
use Symfony\Component\Process\Process;

class TasksRepositoryInstaller implements TasksRepositoryInstallerInterface
{
    /**
     * @var string
     */
    protected string $gitModulesPath;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @var string
     */
    protected string $sdkPath;

    /**
     * @param string $gitModulesPath
     * @param \Psr\Log\LoggerInterface $logger
     * @param string $sdkPath
     */
    public function __construct(string $gitModulesPath, LoggerInterface $logger, string $sdkPath)
    {
        $this->gitModulesPath = $gitModulesPath;
        $this->logger = $logger;
        $this->sdkPath = $sdkPath;
    }

    /**
     * @return array<string|int, bool>
     */
    public function install(): array
    {
        if (!is_file($this->gitModulesPath)) {
            return [];
        }

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

            if (!$process->isSuccessful()) {
                $this->logger->error($process->getErrorOutput());
            }

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
        $process = Process::fromShellCommandline($command, $this->sdkPath);
        $process->run();

        return $process;
    }
}
