<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\Telemetry;

use SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Domain\Enum\SettingPath;
use SprykerSdk\Sdk\Infrastructure\Exception\TelemetryServerUnreachableException;
use Symfony\Component\Serializer\SerializerInterface;

class FileReportTelemetryEventSender implements TelemetryEventSenderInterface
{
    /**
     * @var string
     */
    public const REPORT_FILENAME = 'telemetry_events.json';

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface
     */
    protected ProjectSettingRepositoryInterface $projectSettingRepository;

    /**
     * @var \Symfony\Component\Serializer\SerializerInterface
     */
    protected SerializerInterface $serializer;

    /**
     * @var string
     */
    protected string $reportFileName;

    /**
     * @var string
     */
    protected string $format;

    /**
     * @var bool
     */
    protected bool $isDebug;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface $projectSettingRepository
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     * @param string $reportFileName
     * @param string $format
     * @param bool $isDebug
     */
    public function __construct(
        ProjectSettingRepositoryInterface $projectSettingRepository,
        SerializerInterface $serializer,
        string $reportFileName,
        string $format,
        bool $isDebug = false
    ) {
        $this->projectSettingRepository = $projectSettingRepository;
        $this->serializer = $serializer;
        $this->reportFileName = $reportFileName;
        $this->format = $format;
        $this->isDebug = $isDebug;
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventInterface> $telemetryEvents
     *
     * @throws \SprykerSdk\Sdk\Infrastructure\Exception\TelemetryServerUnreachableException
     *
     * @return void
     */
    public function send(array $telemetryEvents): void
    {
        $reportDirSetting = $this->projectSettingRepository->findOneByPath(SettingPath::REPORT_DIR);

        if ($reportDirSetting === null) {
            return;
        }

        $reportDir = (string)$reportDirSetting->getValues();

        if (!is_dir($reportDir)) {
            mkdir($reportDir, 0777, true);
        }

        $reportFileName = sprintf('%s/%s', $reportDir, static::REPORT_FILENAME);

        // phpcs:ignore
        if (@file_put_contents($reportFileName, $this->serializer->serialize($telemetryEvents, $this->format)) === false) {
            throw new TelemetryServerUnreachableException(sprintf('Can\'t write the %s file: %s', $reportFileName, error_get_last()['message'] ?? ''));
        }
    }

    /**
     * @return bool
     */
    public function isApplicable(): bool
    {
        return $this->isDebug;
    }
}
