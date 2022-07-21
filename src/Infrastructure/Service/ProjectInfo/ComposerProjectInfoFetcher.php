<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\ProjectInfo;

use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Service\ProjectInfo\ProjectInfoInterface;

class ComposerProjectInfoFetcher implements ProjectInfoFetcherStrategyInterface
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
     * @var \SprykerSdk\Sdk\Infrastructure\Service\ProjectInfo\ProjectInfo|null
     */
    protected ?ProjectInfoInterface $projectInfo = null;

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
     * @return \SprykerSdk\Sdk\Infrastructure\Service\ProjectInfo\ProjectInfo
     */
    public function fetchProjectInfo(): ProjectInfoInterface
    {
        if ($this->projectInfo === null) {
            $this->projectInfo = $this->getProjectInfo();
        }

        return $this->projectInfo;
    }

    /**
     * @throws \SprykerSdk\Sdk\Infrastructure\Service\ProjectInfo\FetchDataException
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Service\ProjectInfo\ProjectInfo
     */
    protected function getProjectInfo(): ProjectInfoInterface
    {
        $projectDirectory = $this->settingRepository->findOneByPath(static::PROJECT_DIR_KEY);

        if ($projectDirectory === null) {
            throw new FetchDataException(sprintf('%s setting not found', static::PROJECT_DIR_KEY));
        }

        $projectDirectory = rtrim($projectDirectory->getValues(), DIRECTORY_SEPARATOR);
        $composerFile = $projectDirectory . DIRECTORY_SEPARATOR . static::COMPOSER_FILE_NAME;

        // phpcs:ignore
        $composerJsonContent = @file_get_contents($composerFile);
        if ($composerJsonContent === false) {
            throw new FetchDataException(sprintf('Unable to read the file %s: %s', $composerFile, error_get_last()['message'] ?? ''));
        }

        $composerJson = json_decode($composerJsonContent, true, 512, \JSON_THROW_ON_ERROR);

        if (!isset($composerJson['name'])) {
            throw new FetchDataException(sprintf('%s has no name key', $composerFile));
        }

        return new ProjectInfo($composerJson['name']);
    }
}
