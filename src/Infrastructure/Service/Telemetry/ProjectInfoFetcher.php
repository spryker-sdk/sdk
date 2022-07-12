<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\Telemetry;

use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ProjectInfoFetcher implements ProjectInfoFetcherInterface
{
    /**
     * @var string
     */
    protected const PROJECT_DIR_KEY = 'project_dir';

    /**
     * @var string
     */
    protected const COMPOSER_FILE_NAME = 'composer.json';

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface
     */
    protected SettingRepositoryInterface $settingRepository;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface $settingRepository
     */
    public function __construct(SettingRepositoryInterface $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    /**
     * @return string|null
     */
    public function getProjectName(): ?string
    {
        $projectDirectory = $this->settingRepository->findOneByPath(static::PROJECT_DIR_KEY);

        if ($projectDirectory === null) {
            return $this->getProjectRemoteUrlFromGit();
        }

        $projectDirectory = rtrim($projectDirectory->getValues(), DIRECTORY_SEPARATOR);
        $composerFile = $projectDirectory . DIRECTORY_SEPARATOR . static::COMPOSER_FILE_NAME;

        if (!is_file($composerFile)) {
            return $this->getProjectRemoteUrlFromGit();
        }

        $composerJsonContent = file_get_contents($composerFile);

        if ($composerJsonContent === false) {
            return $this->getProjectRemoteUrlFromGit();
        }

        $composerJson = json_decode($composerJsonContent, true, 512, \JSON_THROW_ON_ERROR);

        if (!isset($composerJson['name'])) {
            return $this->getProjectRemoteUrlFromGit();
        }

        return $composerJson['name'];
    }

    /**
     * @return string|null
     */
    protected function getProjectRemoteUrlFromGit(): ?string
    {
        $process = new Process(['git', 'remote', 'get-url', '--push', 'origin']);

        try {
            $process->mustRun();

            return trim($process->getOutput());
        } catch (ProcessFailedException $e) {
            return null;
        }
    }
}
