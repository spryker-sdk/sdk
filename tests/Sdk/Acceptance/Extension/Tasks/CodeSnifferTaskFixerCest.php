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
    public function testSuccess(AcceptanceTester $I)
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
            $I->getPathFromProjectRoot('reports/' . ReportTelemetryEventSender::REPORT_FILENAME),
        );
    }
}
