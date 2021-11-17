<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Exception\MissingSettingException;
use SprykerSdk\Sdk\Core\Domain\Entity\Setting;
use SprykerSdk\Sdk\Core\Domain\Repository\SettingRepositoryInterface;

class ProjectSettingManager
{
    public function __construct(
        protected ProjectSettingRepositoryInterface $projectSettingRepository,
        protected SettingRepositoryInterface $settingRepository
    ) {}

    /**
     * @param array<string, mixed> $pathValues
     *
     * @return array<Setting>
     */
    public function setSettings(array $pathValues): array
    {
        $projectSettingDefinitions = $this->settingRepository->findProjectSettings();
        $modifiedSettings = [];

        foreach ($projectSettingDefinitions as $projectSettingDefinition) {
            if (isset($pathValues[$projectSettingDefinition->path])) {
                $modifiedSettings[] = $this->buildPathValue($projectSettingDefinition, $pathValues[$projectSettingDefinition->path]);
            }
        }

        $this->projectSettingRepository->saveMultiple($modifiedSettings);

        return $modifiedSettings;
    }

    /**
     * @param string $path
     * @param mixed $value
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Setting
     */
    public function setSetting(string $path, mixed $value): Setting
    {
        $settingDefinition = $this->projectSettingRepository->findOneByPath($path);

        if (!$settingDefinition) {
            throw new MissingSettingException(sprintf('No setting definition for %s found', $path));
        }

        $this->buildPathValue($settingDefinition, $value);

        return $this->projectSettingRepository->save($settingDefinition);
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Setting $settingDefinition
     * @param mixed $value
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Setting
     */
    protected function buildPathValue(Setting $settingDefinition, mixed $value): Setting
    {
        $typedValue = match ($settingDefinition->type) {
            'array' => (array)$value,
            'bool' => (bool)$value,
            default => (string)$value,
        };

        if ($settingDefinition->strategy === 'merge') {
            $typedValue = array_merge($settingDefinition->values, $typedValue);
        }

        $settingDefinition->values = $typedValue;

        return $settingDefinition;
    }
}