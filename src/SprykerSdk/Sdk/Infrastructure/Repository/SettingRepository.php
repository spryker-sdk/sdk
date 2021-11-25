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
use SprykerSdk\Sdk\Infrastructure\Entity\Setting as InfrastructureSetting;
use SprykerSdk\Sdk\Infrastructure\Exception\InvalidTypeException;

/**
 * @extends \Doctrine\ORM\EntityRepository<\SprykerSdk\Sdk\Contracts\Entity\SettingInterface>
 */
class SettingRepository extends EntityRepository implements SettingRepositoryInterface
{
    /**
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $class = $entityManager->getClassMetadata(InfrastructureSetting::class);
        parent::__construct($entityManager, $class);
    }

    /**
     * @param string $settingPath
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\SettingInterface|null
     */
    public function findOneByPath(string $settingPath): ?SettingInterface
    {
        return $this->findOneBy([
            'path' => $settingPath,
        ]);
    }

    /**
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\SettingInterface>
     */
    public function findProjectSettings(): array
    {
        return $this->findBy([
            'isProject' => true,
        ]);
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
     * @throws \SprykerSdk\Sdk\Infrastructure\Exception\InvalidTypeException
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\SettingInterface
     */
    public function save(SettingInterface $setting): SettingInterface
    {
        if (!$setting instanceof InfrastructureSetting) {
            throw new InvalidTypeException('Setting need to be of type ' . InfrastructureSetting::class);
        }

        $this->getEntityManager()->persist($setting);
        $this->getEntityManager()->flush($setting);

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
