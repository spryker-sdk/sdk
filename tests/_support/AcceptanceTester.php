<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Tests;

use Codeception\Actor;
use SprykerSdk\SdkContracts\Enum\Task;
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
    protected const TESTS_DATA_PATH = 'tests/_project';

    /**
     * @param string $relativePath
     *
     * @return string
     */
    public function getPathFromSdkRoot(string $relativePath): string
    {
        return sprintf('%s/%s', dirname(__DIR__, 2), $relativePath);
    }

    /**
     * @return string
     */
    protected function getTestsDataRoot(): string
    {
        return $this->getPathFromSdkRoot(static::TESTS_DATA_PATH);
    }

    /**
     * @param string $relativePath
     *
     * @return string
     */
    public function getPathFromTestsDataRoot(string $relativePath): string
    {
        return sprintf('%s/%s', $this->getTestsDataRoot(), $relativePath);
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
     * @param string $project
     *
     * @return string
     */
    public function getPathFromProjectRoot(string $relativePath, string $project = 'project'): string
    {
        return sprintf('%s/%s', $this->getProjectRoot($project), $relativePath);
    }

    /**
     * @param string $project
     *
     * @return void
     */
    public function cleanReports(string $project = 'project'): void
    {
        $this->cleanDir($this->getPathFromProjectRoot('.ssdk/reports', $project));
    }

    /**
     * @param array<string> $command
     * @param string|null $cwd
     * @param array|null $env
     *
     * @return \Symfony\Component\Process\Process
     */
    public function runSdkCommand(array $command, ?string $cwd = null, ?array $env = null): Process
    {
        $process = new Process(
            array_merge(
                [$this->getPathFromSdkRoot('bin/console')],
                $command,
            ),
            $cwd ?? $this->getProjectRoot(),
            $env,
        );
        $process->setTimeout(null);
        $process->run();

        return $process;
    }

    /**
     * @return void
     */
    public function skipCliInteractiveTest(): void
    {
        if (!Process::isTtySupported()) {
            $this->markTestSkipped(
                sprintf('Task with type "%s" is not supported by CI.', Task::TYPE_LOCAL_CLI_INTERACTIVE),
            );
        }
    }
}
