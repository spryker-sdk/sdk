<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Tests\Helper;

use Codeception\Module;
use Symfony\Component\Process\Process;

class RestApiServerHelper extends Module
{
    /**
     * @var int
     */
    protected const DEFAULT_PORT = 80;

    /**
     * @var int
     */
    protected const STOP_TIMEOUT_SEC = 3;

    /**
     * @var \Symfony\Component\Process\Process
     */
    protected Process $serverProcess;

    /**
     * @param array<string> $settings
     *
     * @return void
     */
    public function _beforeSuite($settings = []): void
    {
        echo(sprintf('Starting rest server %s', PHP_EOL));

        $command = sprintf(
            'php -S localhost:%s -t %s > /dev/null 2>&1',
            $this->config['port'] ?? static::DEFAULT_PORT,
            __DIR__ . '/../../../public',
        );

        $process = Process::fromShellCommandline($command, null, ['PROJECT_DIR'=> __DIR__ . '/../../../']);
        $process->start();
        $this->serverProcess = $process;
        usleep(100000);
    }

    /**
     * @return void
     */
    public function _afterSuite(): void
    {
        echo(sprintf('Stopping rest server %s', PHP_EOL));

        $this->serverProcess->stop(static::STOP_TIMEOUT_SEC);
    }
}
