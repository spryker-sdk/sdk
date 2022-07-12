<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\Telemetry;

use SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ReportTelemetryEventSender implements TelemetryEventSenderInterface
{
    /**
     * @var string
     */
    public const REPORT_FILENAME = 'telemetry_events.json';

    /**
     * @var string
     */
    protected const REPORT_DIR_SETTING_NAME = 'report_dir';

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface
     */
    protected ProjectSettingRepositoryInterface $projectSettingRepository;

    /**
     * @var \Symfony\Component\Serializer\SerializerInterface
     */
    protected SerializerInterface $serializer;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface $projectSettingRepository
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     */
    public function __construct(ProjectSettingRepositoryInterface $projectSettingRepository, SerializerInterface $serializer)
    {
        $this->projectSettingRepository = $projectSettingRepository;
        $this->serializer = $serializer;
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventInterface> $telemetryEvents
     *
     * @return void
     */
    public function send(array $telemetryEvents): void
    {
        $reportDirSetting = $this->projectSettingRepository->findOneByPath(static::REPORT_DIR_SETTING_NAME);

        if ($reportDirSetting === null) {
            return;
        }

        $reportDir = (string)$reportDirSetting->getValues();

        if ($reportDir === '') {
            return;
        }

        if (!is_dir($reportDir)) {
            mkdir($reportDir, 0777, true);
        }

        file_put_contents(sprintf('%s/%s', $reportDir, static::REPORT_FILENAME), $this->serializer->serialize($telemetryEvents, 'json'));
    }
}
