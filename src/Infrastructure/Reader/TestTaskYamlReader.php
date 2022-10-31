<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Reader;

use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\SdkContracts\Entity\SettingInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class TestTaskYamlReader extends TaskYamlReader
{
    /**
     * @var array<string>
     */
    protected array $pathToTestTaskDirs;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface $settingRepository
     * @param \Symfony\Component\Finder\Finder $fileFinder
     * @param \Symfony\Component\Yaml\Yaml $yamlParser
     * @param array<string> $pathToTestTaskDirs
     */
    public function __construct(
        SettingRepositoryInterface $settingRepository,
        Finder $fileFinder,
        Yaml $yamlParser,
        array $pathToTestTaskDirs
    ) {
        parent::__construct($settingRepository, $fileFinder, $yamlParser);
        $this->pathToTestTaskDirs = $pathToTestTaskDirs;
    }

    /**
     * @throws \SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException
     *
     * @return \SprykerSdk\SdkContracts\Entity\SettingInterface
     */
    protected function getExtensionDirsSetting(): SettingInterface
    {
        $taskDirSetting = parent::getExtensionDirsSetting();
        $taskDirSetting->setValues(
            array_merge($this->pathToTestTaskDirs, $taskDirSetting->getValues()),
        );

        dump($taskDirSetting->getValues());
        return $taskDirSetting;
    }
}
