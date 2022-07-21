<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service\Telemetry;

use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEventMetadata;
use SprykerSdk\Sdk\Infrastructure\Service\ProjectInfo\ProjectInfoFetcherInterface;
use SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventMetadataInterface;

class TelemetryEventMetadataFactory implements TelemetryEventMetadataFactoryInterface
{
    /**
     * @var string
     */
    protected const DEVELOPER_EMAIL_KEY = 'developer_email';

    /**
     * @var string
     */
    protected const DEVELOPER_GITHUB_ACCOUNT_KEY = 'developer_github_account';

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\ProjectInfo\ProjectInfoFetcherInterface
     */
    protected ProjectInfoFetcherInterface $projectInfoFetcher;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface
     */
    protected SettingRepositoryInterface $settingRepository;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Service\ProjectInfo\ProjectInfoFetcherInterface $projectInfoFetcher
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface $settingRepository
     */
    public function __construct(ProjectInfoFetcherInterface $projectInfoFetcher, SettingRepositoryInterface $settingRepository)
    {
        $this->projectInfoFetcher = $projectInfoFetcher;
        $this->settingRepository = $settingRepository;
    }

    /**
     * @return \SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventMetadataInterface
     */
    public function createTelemetryEventMetadata(): TelemetryEventMetadataInterface
    {
        $developerEmail = $this->findSettingByKey(static::DEVELOPER_EMAIL_KEY);
        $developerGithubAccount = $this->findSettingByKey(static::DEVELOPER_GITHUB_ACCOUNT_KEY);

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
