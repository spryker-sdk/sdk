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
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\PathResolver $pathResolver
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        protected PathResolver $pathResolver
    ) {
        $class = $entityManager->getClassMetadata(InfrastructureSetting::class);

        parent::__construct($entityManager, $class);
    }

    /**
     * @param string $settingPath
     *
     * @return SettingInterface|null
     */
    public function findOneByPath(string $settingPath): ?SettingInterface
    {
        $setting =  $this->findOneBy([
            'path' => $settingPath
        ]);
        if (!$setting) {
            return null;
        }

        return $this->resolvePathSetting($setting);
    }

    /**
     * @return array<\SprykerSdk\Sdk\Core\Domain\Entity\Setting>
     */
    public function findProjectSettings(): array
    {
        $settings = $this->findBy([
            'isProject' => true,
        ]);
        return array_map(array(static::class, 'resolvePathSetting'), $settings);
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

        $this->getEntityManager()->persist($setting);
        $this->getEntityManager()->flush($setting);

        return $setting;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\SettingInterface $setting
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\SettingInterface|\SprykerSdk\Sdk\Infrastructure\Entity\Setting
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
}
