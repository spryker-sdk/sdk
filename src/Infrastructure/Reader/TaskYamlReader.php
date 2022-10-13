<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Reader;

use RuntimeException;
use SprykerSdk\Sdk\Core\Application\Dependency\ManifestValidatorInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException;
use SprykerSdk\Sdk\Infrastructure\Dto\ManifestCollectionDto;
use SprykerSdk\Sdk\Infrastructure\ManifestValidator\TaskManifestConfiguration;
use SprykerSdk\Sdk\Infrastructure\ManifestValidator\TaskSetManifestConfiguration;
use SprykerSdk\SdkContracts\Enum\Setting;
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
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\ManifestValidatorInterface
     */
    protected ManifestValidatorInterface $manifestValidator;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Dto\ManifestCollectionDto
     */
    protected ManifestCollectionDto $collectionDto;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface $settingRepository
     * @param \Symfony\Component\Finder\Finder $fileFinder
     * @param \Symfony\Component\Yaml\Yaml $yamlParser
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\ManifestValidatorInterface $manifestValidator
     */
    public function __construct(
        SettingRepositoryInterface $settingRepository,
        Finder $fileFinder,
        Yaml $yamlParser,
        ManifestValidatorInterface $manifestValidator
    ) {
        $this->settingRepository = $settingRepository;
        $this->fileFinder = $fileFinder;
        $this->yamlParser = $yamlParser;
        $this->manifestValidator = $manifestValidator;
        $this->collectionDto = new ManifestCollectionDto();
    }

    /**
     * @throws \SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Dto\ManifestCollectionDto
     */
    public function readFiles(): ManifestCollectionDto
    {
        if (count($this->collectionDto->getTasks()) > 0 && count($this->collectionDto->getTaskSets()) > 0) {
            return $this->collectionDto;
        }

        $taskDirSetting = $this->settingRepository->findOneByPath(Setting::PATH_EXTENSION_DIRS);

        if (!$taskDirSetting || !is_array($taskDirSetting->getValues())) {
            throw new MissingSettingException('extension_dirs are not configured properly');
        }

        $finder = $this->fileFinder
            ->in($this->findExistedDirectories($taskDirSetting->getValues()))
            ->name('*.yaml');

        foreach ($finder->files() as $taskFile) {
            $taskData = $this->yamlParser->parse($taskFile->getContents(), Yaml::PARSE_CONSTANT);
            $this->collect($taskData);
        }

        $this->validateCollected();

        return $this->collectionDto;
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

    /**
     * @param array $taskData
     *
     * @throws \RuntimeException
     *
     * @return void
     */
    protected function collect(array $taskData): void
    {
        if ($taskData['type'] === Task::TYPE_TASK_SET) {
            $this->collectionDto->addTaskSet($taskData);

            return;
        }

        if (in_array($taskData['type'], [Task::TYPE_LOCAL_CLI, Task::TYPE_LOCAL_CLI_INTERACTIVE])) {
            $this->collectionDto->addTask($taskData);

            return;
        }

        throw new RuntimeException('Invalid task type ' . $taskData['type']);
    }

    /**
     * @return void
     */
    protected function validateCollected(): void
    {
        $this->collectionDto->setTasks(
            $this->manifestValidator->validate(
                TaskManifestConfiguration::NAME,
                $this->collectionDto->getTasks(),
            ),
        );

        $this->collectionDto->setTaskSets(
            $this->manifestValidator->validate(
                TaskSetManifestConfiguration::NAME,
                $this->collectionDto->getTaskSets(),
            ),
        );
    }
}
