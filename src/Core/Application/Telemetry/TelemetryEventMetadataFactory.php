<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Telemetry;

use SprykerSdk\Sdk\Core\Application\Dependency\Service\ProjectInfo\ProjectInfoFetcherInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\SettingFetcherInterface;
use SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException;
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
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\SettingFetcherInterface
     */
    protected SettingFetcherInterface $settingFetcher;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Service\ProjectInfo\ProjectInfoFetcherInterface $projectInfoFetcher
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\SettingFetcherInterface $settingFetcher
     */
    public function __construct(ProjectInfoFetcherInterface $projectInfoFetcher, SettingFetcherInterface $settingFetcher)
    {
        $this->projectInfoFetcher = $projectInfoFetcher;
        $this->settingFetcher = $settingFetcher;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEventMetadataInterface
     */
    public function createTelemetryEventMetadata(): TelemetryEventMetadataInterface
    {
        $projectInfo = $this->projectInfoFetcher->fetchProjectInfo();
        $projectName = $projectInfo !== null ? $projectInfo->getName() : null;

        return new TelemetryEventMetadata(
            $this->findSettingByKey(Setting::PATH_DEVELOPER_EMAIL),
            $projectName,
            $this->findSettingByKey(Setting::PATH_EXECUTION_ENV),
        );
    }

    /**
     * @param string $settingKey
     *
     * @return string|null
     */
    protected function findSettingByKey(string $settingKey): ?string
    {
        $value = null;
        try {
            $value = $this->settingFetcher->getOneByPath($settingKey)->getValues();
        } catch (MissingSettingException $e) {
        }

        return $value;
    }
}
