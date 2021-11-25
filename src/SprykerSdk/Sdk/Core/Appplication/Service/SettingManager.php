<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Contracts\Entity\SettingInterface;
use SprykerSdk\Sdk\Contracts\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Exception\MissingSettingException;

class SettingManager
{
    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface
     */
    protected ProjectSettingRepositoryInterface $projectSettingRepository;

    /**
     * @var \SprykerSdk\Sdk\Contracts\Repository\SettingRepositoryInterface
     */
    protected SettingRepositoryInterface $settingRepository;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface $projectSettingRepository
     * @param \SprykerSdk\Sdk\Contracts\Repository\SettingRepositoryInterface $settingRepository
     */
    public function __construct(
        ProjectSettingRepositoryInterface $projectSettingRepository,
        SettingRepositoryInterface $settingRepository
    ) {
        $this->settingRepository = $settingRepository;
        $this->projectSettingRepository = $projectSettingRepository;
    }

    /**
     * @param array<string, mixed> $pathValues
     *
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\SettingInterface>
     */
    public function setSettings(array $pathValues): array
    {
        $settingDefinitions = $this->projectSettingRepository->findByPaths($pathValues);
        $modifiedProjectSettings = [];
        $modifiedCoreSettings = [];

        foreach ($settingDefinitions as $settingDefinition) {
            if (isset($pathValues[$settingDefinition->getPath()])) {
                if ($settingDefinition->isProject()) {
                    $modifiedProjectSettings[] = $this->buildPathValue($settingDefinition, $pathValues[$settingDefinition->getPath()]);
                } else {
                    $modifiedCoreSettings[] = $this->buildPathValue($settingDefinition, $pathValues[$settingDefinition->getPath()]);
                }
            }
        }

        if (count($modifiedProjectSettings) > 0) {
            $this->projectSettingRepository->saveMultiple($modifiedProjectSettings);
        }

        if (count($modifiedCoreSettings) > 0) {
            $this->settingRepository->saveMultiple($modifiedCoreSettings);
        }

        return array_merge($modifiedProjectSettings, $modifiedCoreSettings);
    }

    /**
     * @param string $path
     * @param mixed $value
     *
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\MissingSettingException
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\SettingInterface
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
     * @param \SprykerSdk\Sdk\Contracts\Entity\SettingInterface $settingDefinition
     * @param mixed $value
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\SettingInterface
     */
    protected function buildPathValue(SettingInterface $settingDefinition, mixed $value): SettingInterface
    {
        $typedValue = match ($settingDefinition->getType()) {
            'array' => (array)$value,
            'bool' => (bool)$value,
            default => (string)$value,
        };

        if ($settingDefinition->getStrategy() === SettingInterface::STRATEGY_MERGE) {
            $typedValue = array_merge((array)$settingDefinition->getValues(), (array)$typedValue);
        }

        $settingDefinition->setValues($typedValue);

        return $settingDefinition;
    }
}
