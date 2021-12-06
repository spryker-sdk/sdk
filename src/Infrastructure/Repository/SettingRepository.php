<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use SprykerSdk\Sdk\Contracts\Entity\SettingInterface;
use SprykerSdk\Sdk\Contracts\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Service\PathResolver;
use SprykerSdk\Sdk\Infrastructure\Entity\Setting as InfrastructureSetting;
use SprykerSdk\Sdk\Infrastructure\Exception\InvalidTypeException;

/**
 * @extends \Doctrine\ORM\EntityRepository<\SprykerSdk\Sdk\Contracts\Entity\SettingInterface>
 */
class SettingRepository extends EntityRepository implements SettingRepositoryInterface
{
    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\PathResolver
     */
    protected PathResolver $pathResolver;

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\PathResolver $pathResolver
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        PathResolver $pathResolver
    ) {
        $class = $entityManager->getClassMetadata(InfrastructureSetting::class);

        parent::__construct($entityManager, $class);
        $this->pathResolver = $pathResolver;
    }

    /**
     * @param string $settingPath
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\SettingInterface|null
     */
    public function findOneByPath(string $settingPath): ?SettingInterface
    {
        $setting = $this->findOneBy([
            'path' => $settingPath,
        ]);

        if (!$setting) {
            return null;
        }

        return $this->resolvePathSetting($setting);
    }

    /**
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\SettingInterface>
     */
    public function findProjectSettings(): array
    {
        $settings = $this->findBy([
            'isProject' => true,
        ]);

        return array_map([$this, 'resolvePathSetting'], $settings);
    }

    /**
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\SettingInterface>
     */
    public function findCoreSettings(): array
    {
        return $this->findBy([
            'isProject' => false,
        ]);
    }

    /**
     * @param array $paths
     *
     * @return array
     */
    public function findByPaths(array $paths): array
    {
        return $this->findBy([
            'path' => $paths,
        ]);
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\SettingInterface $setting
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\SettingInterface
     */
    public function save(SettingInterface $setting): SettingInterface
    {
        $this->getEntityManager()->persist($setting);
        $this->getEntityManager()->flush($setting);

        return $setting;
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\SettingInterface $setting
     *
     * @throws \SprykerSdk\Sdk\Infrastructure\Exception\InvalidTypeException
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\SettingInterface|\SprykerSdk\Sdk\Infrastructure\Entity\Setting
     */
    protected function resolvePathSetting(SettingInterface $setting)
    {
        if (!$setting instanceof InfrastructureSetting) {
            throw new InvalidTypeException('Setting need to be of type ' . InfrastructureSetting::class);
        }
        if ($setting->getType() === 'path' && !$setting->isProject()) {
            $values = $setting->getValues();
            if (is_array($values)) {
                foreach ($values as $key => $value) {
                    $values[$key] = $this->pathResolver->getResolveRelativePath($value);
                }
            }
            if (is_string($values)) {
                $values = $this->pathResolver->getResolveRelativePath($values);
            }

            $setting->setValues($values);
        }

        return $setting;
    }

    /**
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\SettingInterface> $settings
     *
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\SettingInterface>
     */
    public function saveMultiple(array $settings): array
    {
        foreach ($settings as $setting) {
            $this->save($setting);
        }

        return $settings;
    }
}
