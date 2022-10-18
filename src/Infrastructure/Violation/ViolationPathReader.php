<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Violation;

use SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException;
use SprykerSdk\SdkContracts\Enum\Setting;

class ViolationPathReader
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface
     */
    protected ProjectSettingRepositoryInterface $projectSettingRepository;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface $projectSettingRepository
     */
    public function __construct(
        ProjectSettingRepositoryInterface $projectSettingRepository
    ) {
        $this->projectSettingRepository = $projectSettingRepository;
    }

    /**
     * @param string|null $taskId
     *
     * @return string
     */
    public function getViolationReportPath(?string $taskId): string
    {
        return $this->getViolationReportDirPath() . DIRECTORY_SEPARATOR . $taskId . '.violations.yaml';
    }

    /**
     * @throws \SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException
     *
     * @return string
     */
    public function getViolationReportDirPath(): string
    {
        $reportDirSetting = $this->projectSettingRepository->findOneByPath(Setting::PATH_REPORT_DIR);

        if (!$reportDirSetting) {
            throw new MissingSettingException(sprintf('Some of setting definition for %s not found', Setting::PATH_REPORT_DIR));
        }

        return $reportDirSetting->getValues();
    }
}
