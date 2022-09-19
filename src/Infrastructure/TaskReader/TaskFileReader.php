<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\TaskReader;

use SprykerSdk\Sdk\Core\Application\Dependency\TaskReaderInterface;
use SprykerSdk\Sdk\Core\Application\Dto\TaskCollection;
use SprykerSdk\Sdk\Core\Domain\Enum\TaskType;
use SprykerSdk\SdkContracts\Entity\SettingInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class TaskFileReader implements TaskReaderInterface
{
    /**
     * @var \Symfony\Component\Finder\Finder
     */
    protected Finder $fileFinder;

    /**
     * @var \Symfony\Component\Yaml\Yaml
     */
    protected Yaml $yamlParser;

    /**
     * @param \Symfony\Component\Finder\Finder $fileFinder
     * @param \Symfony\Component\Yaml\Yaml $yamlParser
     */
    public function __construct(Finder $fileFinder, Yaml $yamlParser)
    {
        $this->fileFinder = $fileFinder;
        $this->yamlParser = $yamlParser;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\SettingInterface $taskDirSetting
     *
     * @return \SprykerSdk\Sdk\Core\Application\Dto\TaskCollection
     */
    public function read(SettingInterface $taskDirSetting): TaskCollection
    {
        $taskListData = [];
        $taskSetsData = [];

        $finder = $this->fileFinder
            ->in($this->findExistedDirectories($taskDirSetting->getValues()))
            ->name('*.yaml');

        foreach ($finder->files() as $taskFile) {
            $taskData = $this->yamlParser->parse($taskFile->getContents());

            if ($taskData['type'] === TaskType::TASK_SET_TYPE) {
                $taskSetsData[$taskData['id']] = $taskData;
            } else {
                $taskListData[$taskData['id']] = $taskData;
            }
        }

        return new TaskCollection($taskListData, $taskSetsData);
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
