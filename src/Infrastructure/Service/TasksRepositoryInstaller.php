<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\TasksRepositoryInstallerInterface;
use SprykerSdk\Sdk\Core\Appplication\Exception\RepositoryInstallationFailedException;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use Symfony\Component\Process\Process;

class TasksRepositoryInstaller implements TasksRepositoryInstallerInterface
{
    /**
     * @var string
     */
    protected const SETTING_REPOSITORY = 'repository';

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface;
     */
    protected SettingRepositoryInterface $settingRepository;

    /**
     * @var string
     */
    protected string $installationPath;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface $settingRepository
     * @param string $installationPath
     */
    public function __construct(
        SettingRepositoryInterface $settingRepository,
        string $installationPath
    ) {
        $this->settingRepository = $settingRepository;
        $this->installationPath = $installationPath;
    }

    /**
     * @return bool
     */
    public function install(): bool
    {
        $setting = $this->settingRepository->getOneByPath(static::SETTING_REPOSITORY);
        $repositoryValues = (array)$setting->getValues();

        if (!count($repositoryValues)) {
            return false;
        }

        if (file_exists($this->installationPath)) {
            $this->cleanTasks();
        }

        $this->cloneTasks($repositoryValues);

        return true;
    }

    /**
     * @return void
     */
    protected function cleanTasks(): void
    {
        $directoryIterator = new RecursiveDirectoryIterator($this->installationPath, FilesystemIterator::SKIP_DOTS);
        $recursiveIterator = new RecursiveIteratorIterator($directoryIterator, RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($recursiveIterator as $file) {
            $file->isDir() ? rmdir($file) : unlink($file);
        }
    }

    /**
     * @param array<string> $repositoryValues
     *
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\RepositoryInstallationFailedException
     *
     * @return void
     */
    protected function cloneTasks(array $repositoryValues): void
    {
        $cloneCommand = sprintf(
            'git clone %s %s %s',
            isset($repositoryValues[1]) ? '--branch ' . $repositoryValues[1] . ' --single-branch ' : '',
            $repositoryValues[0],
            $this->installationPath,
        );

        $process = Process::fromShellCommandline($cloneCommand);
        $process->run();

        if ($process->getExitCode() !== ContextInterface::SUCCESS_EXIT_CODE) {
            throw new RepositoryInstallationFailedException($process->getErrorOutput(), (int)$process->getExitCode());
        }
    }
}
