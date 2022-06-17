<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Commands;

use Exception;
use SprykerSdk\Sdk\Core\Appplication\Dependency\LifecycleManagerInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\TasksRepositoryInstallerInterface;
use SprykerSdk\Sdk\Core\Domain\Events\Event;
use SprykerSdk\Sdk\Infrastructure\Exception\SdkVersionNotFoundException;
use SprykerSdk\SdkContracts\Logger\EventLoggerInterface;
use Symfony\Bundle\FrameworkBundle\Command\CacheWarmupCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProcessHelper;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

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
     * @var \Symfony\Component\Console\Helper\ProcessHelper
     */
    protected ProcessHelper $processHelper;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\LifecycleManagerInterface
     */
    protected LifecycleManagerInterface $lifecycleManager;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\TasksRepositoryInstallerInterface
     */
    protected TasksRepositoryInstallerInterface $tasksRepositoryInstaller;

    /**
     * @var \SprykerSdk\SdkContracts\Logger\EventLoggerInterface
     */
    protected EventLoggerInterface $eventLogger;

    /**
     * @param \Symfony\Component\Console\Helper\ProcessHelper $processHelper
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\LifecycleManagerInterface $lifecycleManager
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\TasksRepositoryInstallerInterface $tasksRepositoryInstaller
     * @param \SprykerSdk\SdkContracts\Logger\EventLoggerInterface $eventLogger
     */
    public function __construct(
        ProcessHelper $processHelper,
        LifecycleManagerInterface $lifecycleManager,
        TasksRepositoryInstallerInterface $tasksRepositoryInstaller,
        EventLoggerInterface $eventLogger
    ) {
        parent::__construct(static::$defaultName);
        $this->processHelper = $processHelper;
        $this->lifecycleManager = $lifecycleManager;
        $this->tasksRepositoryInstaller = $tasksRepositoryInstaller;
        $this->eventLogger = $eventLogger;
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
        if ($input->getOption(static::OPTION_NO_CHECK) !== null) {
            $this->checkForUpdate($output);
        }

        if ($input->getOption(static::OPTION_CHECK_ONLY) !== null) {
            $io = new SymfonyStyle($input, $output);

            $io->comment('If you have access to https://github.com/spryker-sdk/sdk-tasks-bundle, please configure SSH connection to Github.');

            $this->installRepository($io);

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

        if (count($messages) === 0) {
            return;
        }

        foreach ($messages as $message) {
            $output->writeln($message->getMessage(), OutputInterface::VERBOSITY_VERBOSE);
        }
    }

    /**
     * @return void
     */
    protected function warmUpCache(): void
    {
        if ($this->getApplication() === null) {
            return;
        }

        /** @var string $cacheWarmupName */
        $cacheWarmupName = CacheWarmupCommand::getDefaultName();

        $this->getApplication()
            ->get($cacheWarmupName)
            ->run(new ArrayInput([]), new NullOutput());
    }

    /**
     * @param \Symfony\Component\Console\Style\SymfonyStyle $io
     *
     * @return void
     */
    protected function installRepository(SymfonyStyle $io): void
    {
        try {
            $isInstalled = $this->tasksRepositoryInstaller->install();

            if ($isInstalled) {
                $io->success('Repository is installed successfully.');

                $this->warmUpCache();
            }
        } catch (Exception $exception) {
            $this->eventLogger->logEvent(new Event(
                static::$defaultName,
                static::class,
                static::$defaultName,
                false,
                'user',
                $exception->getMessage(),
            ));
        }
    }
}
