<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Telemetry;

use SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\Service\Telemetry\TelemetryEventSenderInterface;
use SprykerSdk\Sdk\Core\Application\Exception\TelemetryServerUnreachableException;
use SprykerSdk\SdkContracts\Enum\Setting;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Writes the last event. Is used for debug purposes.
 */
class FileReportTelemetryEventSender implements TelemetryEventSenderInterface
{
    /**
     * @var string
     */
    protected const TRANSPORT_NAME = 'file';

    /**
     * @var string
     */
    public const REPORT_FILENAME = 'telemetry_events.json';

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface
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
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * @var string
     */
    protected string $format;

    /**
     * @var bool
     */
    protected bool $isDebug;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface $projectSettingRepository
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     * @param \Symfony\Component\Filesystem\Filesystem $filesystem
     * @param string $reportFileName
     * @param string $format
     * @param bool $isDebug
     */
    public function __construct(
        ProjectSettingRepositoryInterface $projectSettingRepository,
        SerializerInterface $serializer,
        Filesystem $filesystem,
        string $reportFileName,
        string $format,
        bool $isDebug = false
    ) {
        $this->projectSettingRepository = $projectSettingRepository;
        $this->serializer = $serializer;
        $this->filesystem = $filesystem;
        $this->reportFileName = $reportFileName;
        $this->format = $format;
        $this->isDebug = $isDebug;
    }

    /**
     * @param array<\SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEventInterface> $telemetryEvents
     *
     * @throws \SprykerSdk\Sdk\Core\Application\Exception\TelemetryServerUnreachableException
     *
     * @return void
     */
    public function send(array $telemetryEvents): void
    {
        $reportDirSetting = $this->projectSettingRepository->findOneByPath(Setting::PATH_REPORT_DIR);

        if ($reportDirSetting === null) {
            return;
        }

        $reportFileName = sprintf('%s/%s', $reportDirSetting->getValues(), static::REPORT_FILENAME);

        try {
            $this->filesystem->appendToFile(
                $reportFileName,
                $this->serializer->serialize($telemetryEvents, $this->format) . PHP_EOL,
            );
        } catch (IOException $e) {
            throw new TelemetryServerUnreachableException(sprintf('Can\'t write the %s file: %s', $reportFileName, $e->getMessage()));
        }
    }

    /**
     * @return string
     */
    public function getTransportName(): string
    {
        return static::TRANSPORT_NAME;
    }
}
