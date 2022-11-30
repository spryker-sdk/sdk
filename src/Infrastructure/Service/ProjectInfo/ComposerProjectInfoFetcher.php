<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\ProjectInfo;

use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dto\ProjectInfo\ProjectInfo;
use SprykerSdk\SdkContracts\Enum\Setting;

class ComposerProjectInfoFetcher implements ProjectInfoFetcherStrategyInterface
{
    /**
     * @var string
     */
    protected const COMPOSER_FILE_NAME = 'composer.json';

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dto\ProjectInfo\ProjectInfo|null
     */
    protected ?ProjectInfo $projectInfo = null;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface
     */
    protected SettingRepositoryInterface $settingRepository;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface $settingRepository
     */
    public function __construct(SettingRepositoryInterface $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Application\Dto\ProjectInfo\ProjectInfo
     */
    public function fetchProjectInfo(): ProjectInfo
    {
        if ($this->projectInfo === null) {
            $this->projectInfo = $this->getProjectInfo();
        }

        return $this->projectInfo;
    }

    /**
     * @throws \SprykerSdk\Sdk\Infrastructure\Service\ProjectInfo\FetchDataException
     *
     * @return \SprykerSdk\Sdk\Core\Application\Dto\ProjectInfo\ProjectInfo
     */
    protected function getProjectInfo(): ProjectInfo
    {
        $projectDirectory = $this->settingRepository->findOneByPath(Setting::PATH_PROJECT_DIR);

        if ($projectDirectory === null) {
            throw new FetchDataException(sprintf('%s setting not found', Setting::PATH_PROJECT_DIR));
        }

        $projectDirectory = rtrim($projectDirectory->getValues(), DIRECTORY_SEPARATOR);
        $composerFile = $projectDirectory . DIRECTORY_SEPARATOR . static::COMPOSER_FILE_NAME;

        // phpcs:ignore
        if (!file_exists($composerFile)) {
            throw new FetchDataException(sprintf('Unable to read the file %s: %s', $composerFile, error_get_last()['message'] ?? ''));
        }

        $composerJsonContent = file_get_contents($composerFile);
        $composerJson = json_decode($composerJsonContent, true, 512, \JSON_THROW_ON_ERROR);

        if (!isset($composerJson['name'])) {
            throw new FetchDataException(sprintf('%s has no name key', $composerFile));
        }

        return new ProjectInfo($composerJson['name']);
    }
}
