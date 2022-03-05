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
    public const TESTS_DATA_PATH = 'tests/_support/data';

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
    public function getTestsDataRoot(): string
    {
        return $this->getPathFromSdkRoot(static::TESTS_DATA_PATH);
    }

    /**
     * @param string $project
     *
     * @return string
     */
    public function getProjectRoot(string $project = 'project'): string
    {
        return $this->getPathFromTestsDataRoot($project);
    }

    /**
     * @param string $relativePath
     *
     * @return string
     */
    public function getPathFromTestsDataRoot(string $relativePath): string
    {
        return realpath(sprintf('%s/%s', $this->getTestsDataRoot(), $relativePath));
    }

    /**
     * @return void
     */
    public function cleanReports(string $project = 'project'): void
    {
        $this->cleanDir($this->getPathFromTestsDataRoot("$project/reports"));
    }

    /**
     * @param array<string> $command
     * @param string|null $cwd
     *
     * @return \Symfony\Component\Process\Process
     */
    public function runSdkCommand(array $command, ?string $cwd = null): Process
    {
        $process = new Process(
            array_merge(
                [$this->getPathFromSdkRoot('bin/console')],
                $command,
            ),
            $cwd ?? $this->getProjectRoot(),
        );

        $process->run();

        return $process;
    }
}
