<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Acceptance\Extension\Telemetry;

use PHPUnit\Framework\Assert;
use SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\Payload\CommandExecutionPayload;
use SprykerSdk\Sdk\Infrastructure\Service\Telemetry\FileReportTelemetryEventSender;
use SprykerSdk\Sdk\Tests\AcceptanceTester;

/**
 * @group Acceptance
 * @group Extension
 * @group Tasks
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
    public function testSuccesfullReportSending(AcceptanceTester $I)
    {
        // Arrange
        $I->cleanReports();

        // Act
        $process = $I->runSdkCommand([
            static::COMMAND,
            '--path=src/CodeSniffer/success',
        ]);

        // Assert
        Assert::assertTrue($process->isSuccessful());

        $I->assertTelemetryEventReport(
            static::COMMAND,
            CommandExecutionPayload::getEventName(),
            $I->getPathFromProjectRoot('reports/' . FileReportTelemetryEventSender::REPORT_FILENAME),
        );
    }

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testFailedWhenTelemetryServerUnreachable(AcceptanceTester $I)
    {
        // Arrange
        $I->cleanReports();
        $reportFilename = $I->getPathFromProjectRoot('reports/' . FileReportTelemetryEventSender::REPORT_FILENAME);
        $errorLogFile = $I->getPathFromProjectRoot('.ssdk.log');

        unlink($errorLogFile);
        touch($reportFilename);
        chmod($reportFilename, 0444);

        // Act
        $process = $I->runSdkCommand([
            static::COMMAND,
            '--path=src/CodeSniffer/success',
        ]);

        // Assert
        Assert::assertTrue($process->isSuccessful());
        Assert::assertFileExists($errorLogFile);
        Assert::assertStringContainsString(basename($reportFilename), file_get_contents($errorLogFile));
    }
}