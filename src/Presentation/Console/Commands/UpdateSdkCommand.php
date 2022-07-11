<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Commands;

use Symfony\Component\Console\Helper\ProcessHelper;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class UpdateSdkCommand extends AbstractUpdateCommand
{
    /**
     * @var string
     */
    protected const NAME = 'sdk:update:all';

    /**
     * @var string
     */
    protected string $sdkBasePath;

    /**
     * @var \Symfony\Component\Console\Helper\ProcessHelper
     */
    protected ProcessHelper $processHelper;

    /**
     * @param string $sdkBasePath
     * @param \Symfony\Component\Console\Helper\ProcessHelper $processHelper
     */
    public function __construct(string $sdkBasePath, ProcessHelper $processHelper)
    {
        $this->sdkBasePath = $sdkBasePath;
        $this->processHelper = $processHelper;

        parent::__construct(static::NAME);
    }

    /**
     * @param \Symfony\Component\Console\Input\ArgvInput $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        return (int)!($this->runInstallBundles($output) || $this->runUpdate($input, $output));
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function runInstallBundles(OutputInterface $output): int
    {
        $application = $this->getApplication();

        if (!$application) {
            return static::FAILURE;
        }
        $application->setAutoExit(false);

        return $application->run(new ArrayInput([InstallSdkBundlesCommand::NAME]), $output);
    }

    /**
     * @param \Symfony\Component\Console\Input\ArgvInput $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function runUpdate(InputInterface $input, OutputInterface $output): int
    {
        $process = Process::fromShellCommandline(
            $this->sdkBasePath .
            '/bin/console ' .
            UpdateCommand::NAME .
            str_replace('\'' . static::NAME . '\'', '', (string)$input),
        )->setTty(true);

        $result = $this->processHelper->run(
            $output,
            [$process],
        );

        return (int)$result->getExitCode();
    }
}
