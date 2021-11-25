<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Exception\MissingSettingException;
use SprykerSdk\Sdk\Core\Domain\Entity\Setting;
use SprykerSdk\Sdk\Contracts\Entity\SettingInterface;
use SprykerSdk\Sdk\Contracts\Repository\SettingRepositoryInterface;

class SettingManager
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
        $settingDefinitions = $this->projectSettingRepository->findByPaths(array_keys($pathValues));
        $modifiedSettings = [
            'core' => [],
            'project' => []
        ];

        foreach ($settingDefinitions as $settingDefinition) {
            if (isset($pathValues[$settingDefinition->getPath()])) {
                $settingType = $settingDefinition->isProject() ? 'project' : 'core';
                $modifiedSettings[$settingType][] = $this->buildPathValue($settingDefinition, $pathValues[$settingDefinition->getPath()]);
            }
        }

        if (count($modifiedSettings['project']) > 0) {
            $this->projectSettingRepository->saveMultiple($modifiedSettings['project']);
        }

        if (count($modifiedSettings['core']) > 0) {
            $this->settingRepository->saveMultiple($modifiedSettings['core']);
        }

        return array_merge($modifiedSettings['project'], $modifiedSettings['core']);
    }

    /**
     * @param string $path
     * @param mixed $value
     *
     * @return SettingInterface
     */
    public function setSetting(string $path, mixed $value): SettingInterface
    {
        $settingDefinition = $this->projectSettingRepository->findOneByPath($path);

        if (!$settingDefinition) {
            throw new MissingSettingException(sprintf('No setting definition for %s found', $path));
        }

        $this->buildPathValue($settingDefinition, $value);

        if ($settingDefinition->isProject()) {
            return $this->projectSettingRepository->save($settingDefinition);
        }

        return $this->settingRepository->save($settingDefinition);
    }

    /**
     * @param SettingInterface $settingDefinition
     * @param mixed $value
     *
     * @return SettingInterface
     */
    protected function buildPathValue(SettingInterface $settingDefinition, mixed $value): SettingInterface
    {
        $typedValue = match ($settingDefinition->getType()) {
            'array' => (array)$value,
            'bool' => (bool)$value,
            default => (string)$value,
        };

        if ($settingDefinition->getStrategy() === SettingInterface::STRATEGY_MERGE) {
            $typedValue = array_merge($settingDefinition->getValues(), $typedValue);
        }

        $settingDefinition->setValues($typedValue);

        return $settingDefinition;
    }
}
