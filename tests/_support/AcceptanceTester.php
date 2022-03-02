<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Tests;

use Codeception\Actor;
use Symfony\Component\Process\Process;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class AcceptanceTester extends Actor
{
    use _generated\AcceptanceTesterActions;

    /**
     * @var string
     */
    public const SDK_ROOT = '/data';

    /**
     * @var string
     */
    public const TEST_PROJECT_PATH = 'tests/_support/data/project';

    /**
     * @param string $relativePath
     *
     * @return string
     */
    public function getPathFromSdkRoot(string $relativePath): string
    {
        return realpath(sprintf('%s/%s', dirname(__DIR__, 2), $relativePath));
    }

    /**
     * @return string
     */
    public function getProjectRoot(): string
    {
        return $this->getPathFromSdkRoot(static::TEST_PROJECT_PATH);
    }

    /**
     * @param string $relativePath
     *
     * @return string
     */
    public function getPathFromProjectRoot(string $relativePath): string
    {
        return realpath(sprintf('%s/%s', $this->getProjectRoot(), $relativePath));
    }

    /**
     * @return void
     */
    public function cleanReports(): void
    {
        $this->cleanDir($this->getPathFromProjectRoot('reports'));
    }

    /**
     * @param array<string> $command
     *
     * @return \Symfony\Component\Process\Process
     */
    public function runSdkCommand(array $command): Process
    {
        $process = new Process(
            array_merge(
                [$this->getPathFromSdkRoot('bin/console')],
                $command,
            ),
            $this->getProjectRoot(),
        );

        $process->run();

        return $process;
    }
}
