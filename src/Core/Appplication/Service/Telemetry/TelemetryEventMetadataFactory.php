<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service\Telemetry;

use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEventMetadata;
use SprykerSdk\Sdk\Infrastructure\Service\Telemetry\ProjectInfoFetcherInterface;
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
     * @var \SprykerSdk\Sdk\Infrastructure\Service\Telemetry\ProjectInfoFetcherInterface
     */
    protected ProjectInfoFetcherInterface $projectInfoFetcher;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface
     */
    protected SettingRepositoryInterface $settingRepository;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Service\Telemetry\ProjectInfoFetcherInterface $projectInfoFetcher
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
        $developerEmailSetting = $this->settingRepository->findOneByPath(static::DEVELOPER_EMAIL_KEY);
        $developerEmail = $developerEmailSetting !== null ? $developerEmailSetting->getValues() : null;

        $developerGithubAccountSetting = $this->settingRepository->findOneByPath(static::DEVELOPER_GITHUB_ACCOUNT_KEY);
        $developerGithubAccount = $developerGithubAccountSetting !== null ? $developerGithubAccountSetting->getValues() : null;

        return new TelemetryEventMetadata($developerEmail, $developerGithubAccount, $this->projectInfoFetcher->getProjectName());
    }
}
