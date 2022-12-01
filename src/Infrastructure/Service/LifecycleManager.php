<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use GuzzleHttp\Client;
use SprykerSdk\Sdk\Core\Application\Dependency\LifecycleManagerInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\Sdk\Infrastructure\Exception\SdkVersionNotFoundException;
use SprykerSdk\Sdk\Infrastructure\Loader\TaskYaml\TaskYamlFileLoaderInterface;
use SprykerSdk\Sdk\Infrastructure\Repository\TaskRepository;
use SprykerSdk\Sdk\Infrastructure\Version\FileAppVersionFetcher;
use Throwable;

class LifecycleManager implements LifecycleManagerInterface
{
    /**
     * @var string
     */
    public const GITHUB_VERSION = '0.0.0';

    /**
     * @var string
     */
    public const GITHUB_ENDPOINT = 'https://api.github.com/repos/spryker-sdk/sdk/releases/latest';

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Repository\TaskRepository
     */
    protected TaskRepository $taskEntityRepository;

    /**
     * @var array<\SprykerSdk\Sdk\Core\Application\Dependency\SdkUpdateAction\SdkUpdateActionInterface>
     */
    protected iterable $actions;

    /**
     * @var string
     */
    protected string $sdkDirectory;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Loader\TaskYaml\TaskYamlFileLoaderInterface
     */
    protected TaskYamlFileLoaderInterface $taskYamlFileLoader;

    /**
     * @var string
     */
    protected string $environment;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Loader\TaskYaml\TaskYamlFileLoaderInterface $taskYamlFileLoader
     * @param \SprykerSdk\Sdk\Infrastructure\Repository\TaskRepository $taskEntityRepository
     * @param iterable<\SprykerSdk\Sdk\Core\Application\Dependency\SdkUpdateAction\SdkUpdateActionInterface> $actions
     * @param string $sdkDirectory
     * @param string $environment
     */
    public function __construct(
        TaskYamlFileLoaderInterface $taskYamlFileLoader,
        TaskRepository $taskEntityRepository,
        iterable $actions,
        string $sdkDirectory,
        string $environment
    ) {
        $this->taskYamlFileLoader = $taskYamlFileLoader;
        $this->taskEntityRepository = $taskEntityRepository;
        $this->actions = $actions;
        $this->sdkDirectory = $sdkDirectory;
        $this->environment = $environment;
    }

    /**
     * @return void
     */
    public function update(): void
    {
        $folderTasks = $this->taskYamlFileLoader->loadAll();
        $databaseTasks = $this->taskEntityRepository->findAllIndexedCollection(false);

        foreach ($this->actions as $action) {
            $taskIds = $action->filter($folderTasks, $databaseTasks);

            $action->apply($taskIds, $folderTasks, $databaseTasks);
        }
    }

    /**
     * @return array<\SprykerSdk\SdkContracts\Entity\MessageInterface>
     */
    public function checkForUpdate(): array
    {
        $versionFilePath = $this->sdkDirectory . '/VERSION';

        if (!is_file($versionFilePath)) {
            return [new Message(sprintf('Could not find `%s` file, skip updatable check', FileAppVersionFetcher::VERSION_FILE_NAME))];
        }

        $currentVersion = (string)file_get_contents($versionFilePath);
        $currentVersion = trim($currentVersion);

        if (!$currentVersion) {
            return [new Message(sprintf('Could not find version in the file `%s`. File is empty.', FileAppVersionFetcher::VERSION_FILE_NAME))];
        }

        $messages = [];

        try {
            $latestVersion = $this->getLatestVersion();
        } catch (SdkVersionNotFoundException $exception) {
            $latestVersion = static::GITHUB_VERSION;
            $messages[] = new Message($exception->getMessage());
        }

        if (version_compare($currentVersion, $latestVersion, '<')) {
            $messages[] = new Message(sprintf('SDK is outdated (current: %s, latest: %s)', $currentVersion, $latestVersion));
            $messages[] = new Message('Please update manually by downloading the installer for the newest version at https://github.com/spryker-sdk/sdk/releases');
        }

        return $messages;
    }

    /**
     * @throws \SprykerSdk\Sdk\Infrastructure\Exception\SdkVersionNotFoundException
     *
     * @return string
     */
    protected function getLatestVersion(): string
    {
        $githubEndpoint = 'https://api.github.com/repos/spryker-sdk/sdk/releases/latest';

        $httpClient = new Client();
        try {
            $response = $httpClient->request('GET', $githubEndpoint);
            $content = $response->getBody()->getContents();
            if (!$content) {
                throw new SdkVersionNotFoundException(sprintf('Could not read from %s, error: %s', static::GITHUB_ENDPOINT, error_get_last()['message'] ?? ''));
            }

            $githubContent = json_decode($content, true);
        } catch (Throwable $exception) {
            throw new SdkVersionNotFoundException($exception->getMessage());
        }

        return $githubContent['tag_name'] ?? static::GITHUB_VERSION;
    }
}
