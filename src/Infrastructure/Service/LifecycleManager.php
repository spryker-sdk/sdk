<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use SprykerSdk\Sdk\Core\Appplication\Dependency\LifecycleManagerInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\Sdk\Infrastructure\Exception\SdkVersionNotFoundException;
use SprykerSdk\Sdk\Infrastructure\Repository\TaskRepository;
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
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface
     */
    protected TaskRepositoryInterface $taskYamlRepository;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Repository\TaskRepository
     */
    protected TaskRepository $taskEntityRepository;

    /**
     * @var array<\SprykerSdk\Sdk\Core\Appplication\Dependency\SdkUpdateAction\SdkUpdateActionInterface>
     */
    protected iterable $actions;

    /**
     * @var string
     */
    protected string $sdkDirectory;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface $taskYamlRepository
     * @param \SprykerSdk\Sdk\Infrastructure\Repository\TaskRepository $taskEntityRepository
     * @param iterable<\SprykerSdk\Sdk\Core\Appplication\Dependency\SdkUpdateAction\SdkUpdateActionInterface> $actions
     * @param string $sdkDirectory
     */
    public function __construct(
        TaskRepositoryInterface $taskYamlRepository,
        TaskRepository $taskEntityRepository,
        iterable $actions,
        string $sdkDirectory
    ) {
        $this->taskYamlRepository = $taskYamlRepository;
        $this->taskEntityRepository = $taskEntityRepository;
        $this->actions = $actions;
        $this->sdkDirectory = $sdkDirectory;
    }

    /**
     * @return void
     */
    public function update(): void
    {
        $folderTasks = $this->taskYamlRepository->findAll();
        $databaseTasks = $this->taskEntityRepository->findAllIndexedCollection(false);

        foreach ($this->actions as $action) {
            $taskIds = $action->filter($folderTasks, $databaseTasks);

            $action->apply($taskIds, $folderTasks, $databaseTasks);
        }
    }

    /**
     * @throws \SprykerSdk\Sdk\Infrastructure\Exception\SdkVersionNotFoundException
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\MessageInterface>
     */
    public function checkForUpdate(): array
    {
        $versionFilePath = $this->sdkDirectory . '/VERSION';

        if (!file_exists($versionFilePath)) {
            throw new SdkVersionNotFoundException('Could not find VERSION file, skip updatable check');
        }

        $currentVersion = file_get_contents($versionFilePath);

        if (!$currentVersion) {
            throw new SdkVersionNotFoundException('Could not find VERSION file, skip updatable check');
        }

        $messages = [];
        $currentVersion = trim($currentVersion);

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
        $opts = [
            'http' => [
                'method' => 'GET',
                'header' => [
                    'User-Agent: PHP',
                ],
                'timeout' => 10,
            ],
        ];

        $context = stream_context_create($opts);

        try {
            $content = file_get_contents(static::GITHUB_ENDPOINT, false, $context);

            if (!$content) {
                throw new SdkVersionNotFoundException(sprintf('Could not read from %s', static::GITHUB_ENDPOINT));
            }

            $githubContent = json_decode($content, true);
        } catch (Throwable $exception) {
            throw new SdkVersionNotFoundException($exception->getMessage());
        }

        if (!$githubContent) {
            throw new SdkVersionNotFoundException(sprintf('Could not read from %s', static::GITHUB_ENDPOINT));
        }

        return $githubContent['tag_name'] ?? static::GITHUB_VERSION;
    }
}
