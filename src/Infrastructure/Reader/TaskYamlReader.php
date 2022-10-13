<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Reader;

use RuntimeException;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException;
use SprykerSdk\Sdk\Infrastructure\Dto\ManifestCollectionDto;
use SprykerSdk\SdkContracts\Enum\Task;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class TaskYamlReader
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface
     */
    protected SettingRepositoryInterface $settingRepository;

    /**
     * @var \Symfony\Component\Finder\Finder
     */
    protected Finder $fileFinder;

    /**
     * @var \Symfony\Component\Yaml\Yaml
     */
    protected Yaml $yamlParser;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface $settingRepository
     * @param \Symfony\Component\Finder\Finder $fileFinder
     * @param \Symfony\Component\Yaml\Yaml $yamlParser
     */
    public function __construct(
        SettingRepositoryInterface $settingRepository,
        Finder $fileFinder,
        Yaml $yamlParser
    ) {
        $this->settingRepository = $settingRepository;
        $this->fileFinder = $fileFinder;
        $this->yamlParser = $yamlParser;
    }

    /**
     * @throws \SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Dto\ManifestCollectionDto
     */
    public function readFiles(): ManifestCollectionDto
    {
        $taskDirSetting = $this->settingRepository->findOneByPath('extension_dirs');

        if (!$taskDirSetting || !is_array($taskDirSetting->getValues())) {
            throw new MissingSettingException('extension_dirs are not configured properly');
        }

        $collection = new ManifestCollectionDto();

        $finder = $this->fileFinder
            ->in($this->findExistedDirectories($taskDirSetting->getValues()))
            ->name('*.yaml');

        foreach ($finder->files() as $taskFile) {
            $taskData = $this->yamlParser->parse($taskFile->getContents(), Yaml::PARSE_CONSTANT);

            $this->addTaskToCollection($collection, $taskData);
        }

        return $collection;
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\ManifestCollectionDto $collectionDto
     * @param array $taskData
     *
     * @throws \RuntimeException
     *
     * @return void
     */
    protected function addTaskToCollection(ManifestCollectionDto $collectionDto, array $taskData): void
    {
        if ($taskData['type'] === Task::TYPE_TASK_SET) {
            $collectionDto->addTaskSet($taskData);

            return;
        }

        if (in_array($taskData['type'], [Task::TYPE_LOCAL_CLI, Task::TYPE_LOCAL_CLI_INTERACTIVE])) {
            $collectionDto->addTask($taskData);

            return;
        }

        dump($taskData);

        throw new RuntimeException('Invalid task type ' . $taskData['type']);
    }

    /**
     * @param array<string> $directorySettings
     *
     * @return array<string>
     */
    protected function findExistedDirectories(array $directorySettings): array
    {
        $existingDirs = [];
        foreach ($directorySettings as $directorySetting) {
            $foundOldPaths = glob($directorySetting . '/Task');
            $foundNewPaths = glob($directorySetting . '/task');
            if ($foundOldPaths) {
                $existingDirs[] = $foundOldPaths;
            }
            if ($foundNewPaths) {
                $existingDirs[] = $foundNewPaths;
            }
        }

        return array_merge(...$existingDirs);
    }
}
