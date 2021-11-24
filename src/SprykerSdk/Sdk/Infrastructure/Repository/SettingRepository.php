<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use SprykerSdk\Sdk\Core\Appplication\Service\PathResolver;
use SprykerSdk\Sdk\Core\Domain\Entity\SettingInterface;
use SprykerSdk\Sdk\Core\Domain\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Infrastructure\Entity\Setting as InfrastructureSetting;
use SprykerSdk\Sdk\Infrastructure\Exception\InvalidTypeException;

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
        $this->pathResolver = $pathResolver;

        parent::__construct($entityManager, $class);
    }

    /**
     * @param string $settingPath
     *
     * @return SettingInterface|null
     */
    public function findOneByPath(string $settingPath): ?SettingInterface
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
     * @param SettingInterface $setting
     *
     * @return SettingInterface
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function save(SettingInterface $setting): SettingInterface
    {

        if (!$setting instanceof InfrastructureSetting) {
            throw new InvalidTypeException('Setting need to be of type ' . InfrastructureSetting::class);
        }
        if ($setting->getType() === 'path' && !$setting->isProject()) {
            $values = $setting->getValues();
            if (is_array($values)) {
                foreach ($values as $key => $value) {
                    $values[$key] = $this->pathResolver->getResolveSdkRelativePath($value);
                }
            }
            if (is_string($values)) {
                $values = $this->pathResolver->getResolveSdkRelativePath($values);
            }

            $setting->setValues($values);
        }

        $this->getEntityManager()->persist($setting);
        $this->getEntityManager()->flush($setting);

        return $setting;
    }
}
