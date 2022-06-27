<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Commands;

use SprykerSdk\Sdk\Core\Appplication\Dependency\LifecycleManagerInterface;
use SprykerSdk\Sdk\Infrastructure\Exception\SdkVersionNotFoundException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateCommand extends Command
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
    protected static $defaultDescription = 'Update Spryker SDK to latest version.';

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\LifecycleManagerInterface
     */
    protected LifecycleManagerInterface $lifecycleManager;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\LifecycleManagerInterface $lifecycleManager
     */
    public function __construct(LifecycleManagerInterface $lifecycleManager)
    {
    public function __construct(
        LifecycleManagerInterface $lifecycleManager,
        string $sdkDirectory
    ) {
        parent::__construct(static::$defaultName);
        $this->lifecycleManager = $lifecycleManager;
        $this->sdkDirectory = $sdkDirectory;
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
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->clearCache($output);

//        if ($input->getOption(static::OPTION_NO_CHECK) !== null) {
//            $this->checkForUpdate($output);
//        }

        if ($input->getOption(static::OPTION_CHECK_ONLY) !== null) {
            $this->lifecycleManager->update();
        }

        return static::SUCCESS;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function checkForUpdate(OutputInterface $output): void
    {
        try {
            $messages = $this->lifecycleManager->checkForUpdate();
        } catch (SdkVersionNotFoundException $exception) {
            $output->writeln($exception->getMessage(), OutputInterface::VERBOSITY_VERBOSE);

            return;
        }

        foreach ($messages as $message) {
            $output->writeln($message->getMessage(), OutputInterface::VERBOSITY_VERBOSE);
        }
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function clearCache(OutputInterface $output): void
    {
        $app = $this->getApplication();

        if (!$app) {
            return;
        }

        $app->setAutoExit(false);
        $app->run(new ArrayInput(['command' => 'cache:clear']), $output);
    }
}
