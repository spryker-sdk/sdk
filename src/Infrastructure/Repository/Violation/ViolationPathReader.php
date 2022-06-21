<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository\Violation;

use SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Exception\MissingSettingException;

class ViolationPathReader
{
    /**
     * @var string
     */
    protected const REPORT_DIR_SETTING_NAME = 'report_dir';

    /**
     * @var string
     */
    protected const TEMPLATE_DIR_SETTING_NAME = 'templates_dir';

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface
     */
    protected ProjectSettingRepositoryInterface $projectSettingRepository;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface $projectSettingRepository
     */
    public function __construct(
        ProjectSettingRepositoryInterface $projectSettingRepository
    ) {
        $this->projectSettingRepository = $projectSettingRepository;
    }

    /**
     * @param string|null $taskId
     * @param string $format
     *
     * @return string
     */
    public function getViolationReportPath(?string $taskId, string $format = 'yaml'): string
    {
        return $this->getViolationReportDirPath() . DIRECTORY_SEPARATOR . $taskId . '.violations.' . $format;
    }

    /**
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\MissingSettingException
     *
     * @return string
     */
    public function getViolationReportDirPath(): string
    {
        $reportDirSetting = $this->projectSettingRepository->findOneByPath(static::REPORT_DIR_SETTING_NAME);

        if (!$reportDirSetting) {
            throw new MissingSettingException(sprintf('Some of setting definition for %s not found', static::REPORT_DIR_SETTING_NAME));
        }

        return $reportDirSetting->getValues();
    }

    /**
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\MissingSettingException
     *
     * @return string
     */
    public function getViolationTemplateDirPath(): string
    {
        $reportDirSetting = $this->projectSettingRepository->findOneByPath(static::TEMPLATE_DIR_SETTING_NAME);

        if (!$reportDirSetting) {
            throw new MissingSettingException(sprintf('Some of setting definition for %s not found', static::TEMPLATE_DIR_SETTING_NAME));
        }

        return $reportDirSetting->getValues();
    }
}
