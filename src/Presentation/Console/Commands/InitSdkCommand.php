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
use Symfony\Component\Yaml\Yaml;

class InitSdkCommand extends AbstractInitCommand
{
    /**
     * @var string
     */
    protected static $defaultName = 'sdk:init:sdk';

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
     * @param \Symfony\Component\Yaml\Yaml $yamlParser
     * @param string $settingsPath
     */
    public function __construct(
        string $sdkBasePath,
        ProcessHelper $processHelper,
        Yaml $yamlParser,
        string $settingsPath
    ) {
        $this->sdkBasePath = $sdkBasePath;
        $this->processHelper = $processHelper;
        $this->yamlParser = $yamlParser;
        parent::__construct($yamlParser, $settingsPath);
    }

    /**
     * @param \Symfony\Component\Console\Input\ArgvInput $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        return (int)!($this->runInstallBundles($output) || $this->runInit($input, $output));
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

        return $application->run(new ArrayInput([InstallSdkBundlesCommand::getDefaultName()]), $output);
    }

    /**
     * @param \Symfony\Component\Console\Input\ArgvInput $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function runInit(InputInterface $input, OutputInterface $output): int
    {
        $process = Process::fromShellCommandline(
            $this->sdkBasePath .
            '/bin/console ' .
            InitCommand::NAME .
            str_replace('\'' . static::$defaultName . '\'', '', (string)$input),
        )->setTty(true);
        $result = $this->processHelper->run(
            $output,
            [$process],
        );

        return (int)$result->getExitCode();
    }
}
