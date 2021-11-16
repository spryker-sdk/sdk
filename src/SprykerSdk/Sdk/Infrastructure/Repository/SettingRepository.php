<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository;

use SprykerSdk\Sdk\Core\Domain\Entity\Setting;
use SprykerSdk\Sdk\Core\Domain\Repository\SettingRepositoryInterface;
use Symfony\Component\Yaml\Yaml;

class SettingRepository implements SettingRepositoryInterface
{
    public function __construct(
        protected string $sdkBasePath,
//        protected string $projectConfigurationFile,
//        protected array $basicSettings,
//        protected iterable $settingDefinition,
//        protected Yaml $yamlParser
    ) {
    }

    public function findByPath(string $settingPath): ?Setting
    {
//        $projectSettings = [];
//
//        if (is_readable($this->projectConfigurationFile)) {
//            $projectSettings = $this->yamlParser->parse($this->projectConfigurationFile);
//        }
//
//        foreach ($this->settingDefinition as $settingDefinition) {
//            //translate $projectSettings value into $settingDefinition
//            //-> create new settings definitions for unknown values in $projectSettings
//        }

        //@todo implement properly
        return (new Setting(
            'task_dirs',
            [$this->sdkBasePath . '/Tasks'],
            'merge',
            null,
            true
        ));
    }

    public function save(Setting $setting): Setting
    {
        // TODO: Implement save() method.
    }

}