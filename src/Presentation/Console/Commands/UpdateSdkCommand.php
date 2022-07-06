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

class UpdateSdkCommand extends Command
{
    /**
     * @var string
     */
    public const OPTION_CHECK_ONLY = 'check-only';

    /**
     * @var string
     */
    public const OPTION_NO_CHECK = 'no-check';

    /**
     * @var string
     */
    protected static $defaultName = 'sdk:update:all';

    /**
     * @var string
     */
    protected string $sdkBasePath;

    /**
     * @var string
     */
    protected static $defaultDescription = 'Update Spryker SDK to latest version.';

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

        parent::__construct(static::$defaultName);
    }

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();
        $this->addOption(
            static::OPTION_CHECK_ONLY,
            'c',
            InputOption::VALUE_OPTIONAL,
            'Only checks if the current version is up-to-date',
            false,
        );
        $this->addOption(
            static::OPTION_NO_CHECK,
            null,
            InputOption::VALUE_OPTIONAL,
            'Only checks if the current version is up-to-date',
            false,
        );
    }

    /**
     * @param \Symfony\Component\Console\Input\ArgvInput $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
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
