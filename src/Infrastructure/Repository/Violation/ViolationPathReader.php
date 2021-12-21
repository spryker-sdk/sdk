<?php

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
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface
     */
    protected ProjectSettingRepositoryInterface $projectSettingRepository;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface $projectSettingRepository
     */
    public function __construct(
        ProjectSettingRepositoryInterface $projectSettingRepository,
    ) {
        $this->projectSettingRepository = $projectSettingRepository;
    }

    /**
     * @param string|null $taskId
     *
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\MissingSettingException
     *
     * @return string
     */
    public function getViolationReportPath(?string $taskId): string
    {
        return $this->getViolationReportDirPath() . DIRECTORY_SEPARATOR . $taskId . '.violations.yaml';
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
}
