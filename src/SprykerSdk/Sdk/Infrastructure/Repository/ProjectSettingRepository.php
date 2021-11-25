<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository;

use SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Contracts\Entity\SettingInterface;
use SprykerSdk\Sdk\Contracts\Repository\SettingRepositoryInterface;
use Symfony\Component\Yaml\Yaml;

class ProjectSettingRepository implements ProjectSettingRepositoryInterface
{
    /**
     * @param \SprykerSdk\Sdk\Contracts\Repository\SettingRepositoryInterface $coreSettingRepository
     * @param \Symfony\Component\Yaml\Yaml $yamlParser
     * @param string $projectSettingFileName
     */
    public function __construct(
        protected SettingRepositoryInterface $coreSettingRepository,
        protected Yaml                   $yamlParser,
        protected string                 $projectSettingFileName
    ) {
    }

    /**
     * @param SettingInterface $setting
     *
     * @return SettingInterface
     */
    public function save(SettingInterface $setting): SettingInterface
    {
        return $this->saveMultiple([$setting])[0];
    }

    /**
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\SettingInterface> $settings
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\SettingInterface>
     */
    public function saveMultiple(array $settings): array
    {
        $projectValues = $this->getProjectValues();
        $projectSettingPath = $this->projectSettingFileName;

        foreach ($settings as $setting) {
            $projectValues[$setting->getPath()] = $setting->getValues();
        }

        file_put_contents($projectSettingPath, $this->yamlParser->dump($projectValues));

        return $settings;
    }


    public function findOneByPath(string $settingPath): ?SettingInterface
    {
        $coreSetting = $this->coreSettingRepository->findOneByPath($settingPath);

        if (!$coreSetting) {
            return $coreSetting;
        }

        return $this->fillProjectValues([$coreSetting])[0];
    }

    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Setting[]
     */
    public function find(): array
    {
        $entities = $this->coreSettingRepository->findProjectSettings();

        if (empty($entities)) {
            return $entities;
        }

        return $this->fillProjectValues($entities);
    }

    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Setting[]
     */
    public function findProjectSettings(): array
    {
        return $this->find();
    }


    /**
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\SettingInterface> $entities
     *
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\SettingInterface>
     */
    protected function fillProjectValues(array $entities): array
    {
        $projectValues = $this->getProjectValues();

        foreach ($entities as $entity) {
            if (!$entity->isProject()) {
                continue;
            }

            if (array_key_exists($entity->getPath(), $projectValues)) {
                $entity->setValues($projectValues[$entity->getPath()]);
            }
        }

        return $entities;
    }

    /**
     * @return array
     */
    protected function getProjectValues(): array
    {
        $projectSettingPath = $this->projectSettingFileName;

        if (!is_readable($projectSettingPath)) {
            return [];
        }

        return $this->yamlParser->parseFile($projectSettingPath);
    }

    /**
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\SettingInterface>
     */
    public function findCoreSettings(): array
    {
        return $this->coreSettingRepository->findCoreSettings();
    }

    /**
     * @param array<string> $paths
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\SettingInterface[]
     */
    public function findByPaths(array $paths): array
    {
        return $this->fillProjectValues(
            $this->coreSettingRepository->findByPaths($paths)
        );
    }
}
