<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Commands;

use Composer\InstalledVersions;
use SprykerSdk\Sdk\Core\Appplication\Dependency\LifecycleManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProcessHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class UpdateCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'update';

    /**
     * @var string
     */
    protected static $defaultDescription = 'Update Spryker SDK to latest version.';

    protected ProcessHelper $processHelper;

    protected LifecycleManagerInterface $lifecycleManager;

    /**
     * @param \Symfony\Component\Console\Helper\ProcessHelper $processHelper
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\LifecycleManagerInterface $lifecycleManager
     */
    public function __construct(
        ProcessHelper $processHelper,
        LifecycleManagerInterface $lifecycleManager
    ) {
        parent::__construct(static::$defaultName);
        $this->processHelper = $processHelper;
        $this->lifecycleManager = $lifecycleManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $sdkPackage = InstalledVersions::getRootPackage();
        $sdkPackageName = $sdkPackage['name'];
        $sdkPackageVersion = $sdkPackage['pretty_version'];

        $process = Process::fromShellCommandline(sprintf('composer update %s', $sdkPackageName));
        $process->setTimeout(null);
        $process->setIdleTimeout(null);

        $process = $this->processHelper->run($output, [$process]);

        $exitCode = (int)$process->getExitCode();

        if ($exitCode !== 0) {
            $output->writeln($process->getErrorOutput());

            return $exitCode;
        }

        $this->lifecycleManager->update();

        return $exitCode;
    }
}
