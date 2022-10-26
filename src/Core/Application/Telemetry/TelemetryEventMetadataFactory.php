<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Telemetry;

use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\Service\ProjectInfo\ProjectInfoFetcherInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEventMetadata;
use SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEventMetadataInterface;
use SprykerSdk\SdkContracts\Enum\Setting;

class TelemetryEventMetadataFactory implements TelemetryEventMetadataFactoryInterface
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\Service\ProjectInfo\ProjectInfoFetcherInterface
     */
    protected ProjectInfoFetcherInterface $projectInfoFetcher;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface
     */
    protected SettingRepositoryInterface $settingRepository;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Service\ProjectInfo\ProjectInfoFetcherInterface $projectInfoFetcher
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface $settingRepository
     */
    public function __construct(ProjectInfoFetcherInterface $projectInfoFetcher, SettingRepositoryInterface $settingRepository)
    {
        $this->projectInfoFetcher = $projectInfoFetcher;
        $this->settingRepository = $settingRepository;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEventMetadataInterface
     */
    public function createTelemetryEventMetadata(): TelemetryEventMetadataInterface
    {
        $developerEmail = $this->findSettingByKey(Setting::PATH_DEVELOPER_EMAIL);
        $developerGithubAccount = $this->findSettingByKey(Setting::PATH_DEVELOPER_GITHUB_ACCOUNT);

        $projectInfo = $this->projectInfoFetcher->fetchProjectInfo();
        $projectName = $projectInfo !== null ? $projectInfo->getName() : null;

        return new TelemetryEventMetadata($developerEmail, $developerGithubAccount, $projectName);
    }

    /**
     * @param string $settingKey
     *
     * @return string|null
     */
    protected function findSettingByKey(string $settingKey): ?string
    {
        $setting = $this->settingRepository->findOneByPath($settingKey);

        return $setting !== null ? $setting->getValues() : null;
    }
}
