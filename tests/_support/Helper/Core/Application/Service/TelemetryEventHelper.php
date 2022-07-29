<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Tests\Helper\Core\Application\Service;

use Codeception\Module;

class TelemetryEventHelper extends Module
{
    /**
     * @param string $command
     * @param string $eventName
     * @param string $fileName
     *
     * @return void
     */
    public function assertTelemetryEventReport(string $command, string $eventName, string $fileName): void
    {
        $this->assertFileExists($fileName);

        $reportContent = file_get_contents($fileName);
        $reportJson = json_decode($reportContent, true, 512, JSON_THROW_ON_ERROR);
        $reportJsonItem = $reportJson[0];

        $this->assertArrayHasKey('name', $reportJsonItem);
        $this->assertArrayHasKey('version', $reportJsonItem);
        $this->assertArrayHasKey('scope', $reportJsonItem);
        $this->assertArrayHasKey('triggered_at', $reportJsonItem);
        $this->assertArrayHasKey('pushed_at', $reportJsonItem);
        $this->assertArrayHasKey('payload', $reportJsonItem);
        $this->assertArrayHasKey('metadata', $reportJsonItem);
        $this->assertArrayHasKey('command_name', $reportJsonItem['payload']);

        $this->assertSame($eventName, $reportJsonItem['name']);
        $this->assertSame($command, $reportJsonItem['payload']['command_name']);

        $this->assertSame('spryker-sdk/sdk-test', $reportJsonItem['metadata']['project_name']);
    }
}
