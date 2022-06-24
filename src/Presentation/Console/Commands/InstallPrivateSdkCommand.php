<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Commands;

use Exception;
use SprykerSdk\Sdk\Core\Appplication\Dependency\TasksRepositoryInstallerInterface;
use SprykerSdk\Sdk\Core\Domain\Events\Event;
use SprykerSdk\SdkContracts\Logger\EventLoggerInterface;
use Symfony\Bundle\FrameworkBundle\Command\CacheClearCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class InstallPrivateSdkCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'sdk:update:private';

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\TasksRepositoryInstallerInterface
     */
    protected TasksRepositoryInstallerInterface $tasksRepositoryInstaller;

    /**
     * @var \SprykerSdk\SdkContracts\Logger\EventLoggerInterface
     */
    protected EventLoggerInterface $eventLogger;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\TasksRepositoryInstallerInterface $tasksRepositoryInstaller
     * @param \SprykerSdk\SdkContracts\Logger\EventLoggerInterface $eventLogger
     */
    public function __construct(
        TasksRepositoryInstallerInterface $tasksRepositoryInstaller,
        EventLoggerInterface $eventLogger
    ) {
        parent::__construct(static::$defaultName);
        $this->tasksRepositoryInstaller = $tasksRepositoryInstaller;
        $this->eventLogger = $eventLogger;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->comment('If you have access to https://github.com/spryker-sdk/sdk-tasks-bundle, please configure SSH connection to Github.');

        $this->installRepository($io);

        return static::SUCCESS;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function warmUpCache(OutputInterface $output): void
    {
        if (!$application = $this->getApplication()) {
            return;
        }

        /** @var string $cacheWarmupName */
        $cacheWarmupName = CacheClearCommand::getDefaultName();

        $application
            ->get($cacheWarmupName)
            ->run(new ArrayInput([]), $output);
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

                $this->warmUpCache($io);
            }
        } catch (Exception $exception) {
            $io->error([
                'Repository installation failed.',
                sprintf('Details: %s', $exception->getMessage()),
            ]);

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
