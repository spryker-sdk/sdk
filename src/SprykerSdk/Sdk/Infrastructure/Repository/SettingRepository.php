<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use SprykerSdk\Sdk\Core\Domain\Entity\Setting;
use SprykerSdk\Sdk\Core\Domain\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Infrastructure\Entity\Setting as InfrastructureSetting;
use SprykerSdk\Sdk\Infrastructure\Exception\InvalidTypeException;

class SettingRepository extends EntityRepository implements SettingRepositoryInterface
{
    /**
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(
        EntityManagerInterface $entityManager,
    ) {
        $class = $entityManager->getClassMetadata(InfrastructureSetting::class);
        parent::__construct($entityManager, $class);
    }

    /**
     * @param string $settingPath
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Setting|null
     */
    public function findOneByPath(string $settingPath): ?Setting
    {
        return $this->findOneBy([
            'path' => $settingPath
        ]);
    }

    /**
     * @return array<\SprykerSdk\Sdk\Core\Domain\Entity\Setting>
     */
    public function findProjectSettings(): array
    {
        return $this->findBy([
            'isProject' => true,
        ]);
    }


    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Setting $setting
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Setting
     */
    public function save(Setting $setting): Setting
    {
        if (!$setting instanceof InfrastructureSetting) {
            throw new InvalidTypeException('Setting need to be of type ' . InfrastructureSetting::class);
        }

        $this->getEntityManager()->persist($setting);
        $this->getEntityManager()->flush($setting);

        return $setting;
    }
}