<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Acceptance\Telemetry;

use PHPUnit\Framework\Assert;
use SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\Payload\CommandExecutionPayload;
use SprykerSdk\Sdk\Infrastructure\Service\Telemetry\ReportTelemetryEventSender;
use SprykerSdk\Sdk\Tests\AcceptanceTester;

class TelemetryEventSenderCest
{
    /**
     * @var string
     */
    protected const COMMAND = 'list';

    /**
     * @var string
     */
    protected const PROJECT_DIR = 'telemetry_events_sender_project';

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testTelemetryEventsPushedSuccessfully(AcceptanceTester $I): void
    {
        // Arrange
        $I->cleanReports(static::PROJECT_DIR);

        // Act
        $process = $I->runSdkCommand(
            [
                static::COMMAND,
            ],
            $I->getProjectRoot(static::PROJECT_DIR),
        );

        // Assert
        Assert::assertTrue($process->isSuccessful());

        $reportFileName = $I->getPathFromProjectRoot('reports/' . ReportTelemetryEventSender::REPORT_FILENAME, static::PROJECT_DIR);
        Assert::assertFileExists($reportFileName);

        $I->assertTelemetryEventReport(static::COMMAND, CommandExecutionPayload::getEventName(), $reportFileName);
    }
}
