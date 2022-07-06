<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProcessHelper;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Yaml;

class InitSdkCommand extends Command
{
    /**
     * @var string|null The default command name
     */
    protected static $defaultName = 'sdk:init:sdk';

    /**
     * @var string
     */
    protected string $sdkBasePath;

    /**
     * @var \Symfony\Component\Yaml\Yaml
     */
    protected Yaml $yamlParser;

    /**
     * @var string
     */
    protected string $settingsPath;

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
        $this->settingsPath = $settingsPath;
        $this->yamlParser = $yamlParser;
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $settings = $this->yamlParser->parseFile($this->settingsPath)['settings'];

        foreach ($settings as $settingData) {
            $mode = InputOption::VALUE_REQUIRED;
            if ($settingData['strategy'] === 'merge') {
                $mode |= InputOption::VALUE_IS_ARRAY;
            }
            $this->addOption(
                $settingData['path'],
                null,
                $mode,
                $settingData['initialization_description'],
            );
        }
    }

    /**
     * @param \Symfony\Component\Console\Input\ArgvInput $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $application = $this->getApplication();

        if (!$application) {
            return static::FAILURE;
        }
        $application->setAutoExit(false);
        $application->run(new ArrayInput([InstallSdkBundlesCommand::getDefaultName()]), $output);

        $process = Process::fromShellCommandline(
            $this->sdkBasePath .
            '/bin/console ' .
            InitCommand::NAME .
            str_replace('\'' . static::$defaultName . '\'', '', (string)$input),
        )->setTty(true);
        $this->processHelper->run(
            $output,
            [$process],
        );

        return static::SUCCESS;
    }
}
