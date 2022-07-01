<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Commands;

use GuzzleHttp\Client;
use SprykerSdk\Sdk\Core\Appplication\Dependency\LifecycleManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

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
     * @var string
     */
    protected string $sdkDirectory;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\LifecycleManagerInterface $lifecycleManager
     * @param string $sdkDirectory
     */
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
        $latestVersion = $this->getLatestVersion($output);
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
    protected function checkForUpdate(OutputInterface $output)
    {
        $versionFilePath = $this->sdkDirectory . '/VERSION';

        if (!file_exists($versionFilePath)) {
            $output->writeln('<error>Could not find VERSION file, skip updatable check</error>', OutputInterface::VERBOSITY_VERBOSE);

            return;
        }

        $currentVersion = file_get_contents($versionFilePath);

        if (!$currentVersion) {
            $output->writeln('<error>Could not read VERSION file, skip updatable check</error>', OutputInterface::VERBOSITY_VERBOSE);

            return;
        }
        $currentVersion = trim($currentVersion);
        $latestVersion = $this->getLatestVersion($output);

        if (version_compare($currentVersion, $latestVersion, '<')) {
            $output->writeln(sprintf('SDK is outdated (current: %s, latest: %s)', $currentVersion, $latestVersion));
            $output->writeln('Please update manually by downloading the installer for the newest version at https://github.com/spryker-sdk/sdk/releases');
        }
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return string
     */
    protected function getLatestVersion(OutputInterface $output): string
    {
        $githubVersion = '0.0.0';
        $githubEndpoint = 'https://api.github.com/repos/spryker-sdk/sdk/releases/latest';

        $httpClient = new Client();
        try {
            $response = $httpClient->request('GET', $githubEndpoint);
            $content = $response->getBody()->getContents();
            if (!$content) {
                $output->writeln(sprintf('<error>Could not read from %s</error>', $githubEndpoint), OutputInterface::VERBOSITY_VERBOSE);

                return $githubVersion;
            }

            $githubContent = json_decode($content, true);
        } catch (Throwable $exception) {
            $output->writeln('<error>' . $exception->getMessage() . '</error>', OutputInterface::VERBOSITY_VERBOSE);

            return $githubVersion;
        }

        if (!$githubContent) {
            $output->writeln(sprintf('<error>Could not read version from %s</error>', $githubEndpoint), OutputInterface::VERBOSITY_VERBOSE);

            return $githubVersion;
        }

        return $githubContent['tag_name'] ?? '0.0.0';
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
