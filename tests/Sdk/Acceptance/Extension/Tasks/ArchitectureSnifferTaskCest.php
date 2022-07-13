<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Acceptance\Extension\Tasks;

use PHPUnit\Framework\Assert;
use SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\Payload\CommandExecutionPayload;
use SprykerSdk\Sdk\Infrastructure\Service\Telemetry\ReportTelemetryEventSender;
use SprykerSdk\Sdk\Tests\AcceptanceTester;

class ArchitectureSnifferTaskCest
{
    /**
     * @var string
     */
    protected const COMMAND = 'validation:php:architecture';

    /**
     * @var string
     */
    protected const PROJECT_DIR = 'architecture_project';

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testArchitectureSnifferRunsSuccessfully(AcceptanceTester $I): void
    {
        // Arrange
        $I->cleanReports(static::PROJECT_DIR);

        // Act
        $process = $I->runSdkCommand(
            [
                static::COMMAND,
                '--strict=no',
                '--priority=2',
            ],
            $I->getProjectRoot(static::PROJECT_DIR),
        );

        // Assert
        Assert::assertTrue($process->isSuccessful());

        $I->assertTelemetryEventReport(
            static::COMMAND,
            CommandExecutionPayload::getEventName(),
            $I->getPathFromProjectRoot('reports/' . ReportTelemetryEventSender::REPORT_FILENAME, static::PROJECT_DIR),
        );
    }

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testArchitectureSnifferFindingViolations(AcceptanceTester $I): void
    {
        // Arrange
        $I->cleanReports(static::PROJECT_DIR);

        // Act
        $process = $I->runSdkCommand(
            [
                static::COMMAND,
                '--strict=no',
                '--priority=3',
            ],
            $I->getProjectRoot(static::PROJECT_DIR),
        );

        // Assert
        Assert::assertFalse($process->isSuccessful());

        $I->assertTelemetryEventReport(
            static::COMMAND,
            CommandExecutionPayload::getEventName(),
            $I->getPathFromProjectRoot('reports/' . ReportTelemetryEventSender::REPORT_FILENAME, static::PROJECT_DIR),
        );
    }
}
