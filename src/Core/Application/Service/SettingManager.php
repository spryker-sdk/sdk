<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Service;

use SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException;
use SprykerSdk\SdkContracts\Entity\SettingInterface;

class SettingManager
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface
     */
    protected ProjectSettingRepositoryInterface $projectSettingRepository;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface
     */
    protected SettingRepositoryInterface $settingRepository;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface $projectSettingRepository
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface $settingRepository
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
     * @return array<\SprykerSdk\SdkContracts\Entity\SettingInterface>
     */
    public function setSettings(array $pathValues): array
    {
        $settingDefinitions = $this->projectSettingRepository->findByPaths(array_keys($pathValues));
        $modifiedProjectSettings = [];
        $modifiedCoreSettings = [];

        foreach ($settingDefinitions as $settingDefinition) {
            if (isset($pathValues[$settingDefinition->getPath()])) {
                $setting = $this->buildPathValue($settingDefinition, $pathValues[$settingDefinition->getPath()]);

                if ($settingDefinition->isProject()) {
                    $modifiedProjectSettings[] = $setting;
                } else {
                    $modifiedCoreSettings[] = $setting;
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
     * @throws \SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException
     *
     * @return \SprykerSdk\SdkContracts\Entity\SettingInterface
     */
    public function setSetting(string $path, $value): SettingInterface
    {
        /** @var \SprykerSdk\Sdk\Core\Domain\Entity\Setting|null $settingDefinition */
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
     * @param \SprykerSdk\SdkContracts\Entity\SettingInterface $settingDefinition
     * @param mixed $value
     *
     * @return \SprykerSdk\SdkContracts\Entity\SettingInterface
     */
    protected function buildPathValue(SettingInterface $settingDefinition, $value): SettingInterface
    {
        $typedValue = is_array($value) ?
            (array)$value :
            ['array' => (array)$value, 'boolean' => (bool)$value][$settingDefinition->getType()] ?? (string)$value;

        if ($settingDefinition->getStrategy() === SettingInterface::STRATEGY_MERGE) {
            $typedValue = array_merge((array)$settingDefinition->getValues(), (array)$typedValue);
            $typedValue = array_unique($typedValue);
        }

        $settingDefinition->setValues($typedValue);

        return $settingDefinition;
    }
}
