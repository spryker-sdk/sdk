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
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

class InstallSdkBundlesCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'sdk:update:bundles';

    /**
     * @var string|null The default command description
     */
    protected static $defaultDescription = 'The command updates submodules. Please configure SSH connection to Github.';

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\TasksRepositoryInstallerInterface
     */
    protected TasksRepositoryInstallerInterface $tasksRepositoryInstaller;

    /**
     * @var string
     */
    protected string $sdkBasePath;

    /**
     * @var \SprykerSdk\SdkContracts\Logger\EventLoggerInterface
     */
    protected EventLoggerInterface $eventLogger;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\TasksRepositoryInstallerInterface $tasksRepositoryInstaller
     * @param string $sdkBasePath
     * @param \SprykerSdk\SdkContracts\Logger\EventLoggerInterface $eventLogger
     */
    public function __construct(
        TasksRepositoryInstallerInterface $tasksRepositoryInstaller,
        string $sdkBasePath,
        EventLoggerInterface $eventLogger
    ) {
        parent::__construct(static::$defaultName);
        $this->tasksRepositoryInstaller = $tasksRepositoryInstaller;
        $this->sdkBasePath = $sdkBasePath;
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
        if (!$this->getApplication()) {
            return;
        }

        /** @var string $cacheCommandName */
        $cacheCommandName = CacheClearCommand::getDefaultName();

        $process = Process::fromShellCommandline($this->sdkBasePath . '/bin/console ' . $cacheCommandName);
        $process->run();
    }

    /**
     * @param \Symfony\Component\Console\Style\SymfonyStyle $io
     *
     * @return void
     */
    protected function installRepository(SymfonyStyle $io): void
    {
        try {
            $installationModules = $this->tasksRepositoryInstaller->install();

            foreach ($installationModules as $module => $result) {
                $result ? $io->success(sprintf('Module "%s" is installed successfully.', $module)) :
                    $io->info(sprintf('Module "%s" was not installed successfully. Please check permission to repository.', $module));
            }

            $this->warmUpCache($io);
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
