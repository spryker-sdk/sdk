<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Acceptance\Telemetry;

use PHPUnit\Framework\Assert;
use SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\Payload\CommandExecutionPayload;
use SprykerSdk\Sdk\Infrastructure\Telemetry\FileReportTelemetryEventSender;
use SprykerSdk\Sdk\Tests\AcceptanceTester;

/**
 * @group Acceptance
 * @group Extension
 * @group Telemetry
 * @group CodeSnifferTaskFixerCest
 */
class CodeSnifferTaskFixerCest
{
    /**
     * @var string
     */
    protected const COMMAND = 'validation:php:codestyle-fix';

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testSuccesfullReportSending(AcceptanceTester $I): void
    {
        // Arrange
        $I->cleanReports();

        // Act
        $process = $I->runSdkCommand([
            static::COMMAND,
            '--path=src/CodeSniffer/success',
        ], null, ['TELEMETRY_ENABLED' => 'true', 'TELEMETRY_TRANSPORT' => 'file']);

        // Assert
        Assert::assertTrue($process->isSuccessful());

        $I->assertTelemetryEventReport(
            static::COMMAND,
            CommandExecutionPayload::EVENT_NAME,
            $I->getPathFromProjectRoot('.ssdk/reports/' . FileReportTelemetryEventSender::REPORT_FILENAME),
        );
    }

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testFailedWhenTelemetryServerUnreachable(AcceptanceTester $I): void
    {
        // Arrange
        $I->cleanReports();
        $reportFilename = $I->getPathFromProjectRoot('.ssdk/reports/' . FileReportTelemetryEventSender::REPORT_FILENAME);
        $errorLogFile = $I->getPathFromProjectRoot('.ssdk/.ssdk.log');

        if (is_file($errorLogFile)) {
            unlink($errorLogFile);
        }
        touch($reportFilename);
        chmod($reportFilename, 0444);

        // Act
        $process = $I->runSdkCommand([
            static::COMMAND,
            '--path=src/CodeSniffer/success',
        ], null, ['TELEMETRY_ENABLED' => 'true', 'TELEMETRY_TRANSPORT' => 'file']);

        // Assert
        Assert::assertTrue($process->isSuccessful());
        Assert::assertStringContainsString(basename($reportFilename), file_get_contents($errorLogFile));
    }
}
