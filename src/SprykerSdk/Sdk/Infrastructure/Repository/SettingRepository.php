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
use Symfony\Component\Yaml\Yaml;

class SettingRepository extends EntityRepository implements SettingRepositoryInterface
{
    /**
     * @param \Doctrine\ORM\EntityManagerInterface $em
     * @param \Symfony\Component\Yaml\Yaml $yamlParser
     * @param string $projectSettingFileName
     */
    public function __construct(
        EntityManagerInterface $em,
        protected Yaml $yamlParser,
        protected string $projectSettingFileName
    ) {
        $class = $em->getClassMetadata(InfrastructureSetting::class);

        parent::__construct($em, $class);
    }

    /**
     * @return @return array<\SprykerSdk\Sdk\Infrastructure\Entity\Setting>
     */
    public function findProjectSettings(): array
    {
        return parent::findBy([
            'isProject' => true
        ]);
    }


    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param null $limit
     * @param null $offset
     *
     * @return array<\SprykerSdk\Sdk\Infrastructure\Entity\Setting>
     */
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
    {
        /** @var \SprykerSdk\Sdk\Infrastructure\Entity\Setting[] $entities */
        $entities = parent::findBy($criteria, $orderBy, $limit, $offset);

        if (empty($entities)) {
            return $entities;
        }

        return $this->fillProjectValues($entities);
    }

    /**
     * @param string $settingPath
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Setting|null
     */
    public function findOneByPath(string $settingPath): ?Setting
    {
        $entity = $this->findOneBy([
            'path' => $settingPath
        ]);

        if (!$entity) {
            return $entity;
        }

        return $this->fillProjectValues([$entity])[0];
    }

    /**
     * @param string $settingPath
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Setting|null
     */
    public function findOneDefinitionByPath(string $settingPath): ?Setting
    {
        return parent::findOneBy(['path' => $settingPath]);
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
            //@todo throw persisting exception
        }

        $this->getEntityManager()->persist($setting);
        $this->getEntityManager()->flush($setting);

        return $setting;
    }

    /**
     * @param array<InfrastructureSetting> $entities
     *
     * @return array<InfrastructureSetting>
     */
    protected function fillProjectValues(array $entities): array
    {
        $projectSettingPath = getcwd() . '/' . $this->projectSettingFileName;

        if (!is_readable($projectSettingPath)) {
            //@todo throw exception '.ssdk file not found, please go to the project directory or call spryker-sdk init'
        }

        $projectValues = $this->yamlParser->parseFile($projectSettingPath);

        foreach ($entities as $entity) {
            if (!$entity->isProject) {
                continue;
            }

            if (array_key_exists($entity->path, $projectValues)) {
                $entity->values = $projectValues[$entity->path];
            }
        }

        return $entities;
    }

}