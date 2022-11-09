<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository;

use Doctrine\DBAL\Exception\TableNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException;
use SprykerSdk\Sdk\Core\Application\Exception\SettingsNotInitializedException;
use SprykerSdk\Sdk\Infrastructure\Entity\Setting as EntitySetting;
use SprykerSdk\Sdk\Infrastructure\Entity\Setting as InfrastructureSetting;
use SprykerSdk\Sdk\Infrastructure\Exception\InvalidTypeException;
use SprykerSdk\Sdk\Infrastructure\Resolver\PathResolver;
use SprykerSdk\SdkContracts\Entity\SettingInterface;
use SprykerSdk\SdkContracts\Entity\SettingInterface as EntitySettingInterface;
use SprykerSdk\SdkContracts\Enum\Setting;
use SprykerSdk\SdkContracts\Enum\ValueTypeEnum;
use Symfony\Component\Yaml\Yaml;

/**
 * @extends \Doctrine\ORM\EntityRepository<\SprykerSdk\SdkContracts\Entity\SettingInterface>
 */
class SettingRepository extends EntityRepository implements SettingRepositoryInterface
{
    /**
     * @var \Symfony\Component\Yaml\Yaml
     */
    protected Yaml $yamlParser;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Resolver\PathResolver
     */
    protected PathResolver $pathResolver;

    /**
     * @var string
     */
    protected string $settingsPath;

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \SprykerSdk\Sdk\Infrastructure\Resolver\PathResolver $pathResolver
     * @param \Symfony\Component\Yaml\Yaml $yamlParser
     * @param string $settingsPath
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        PathResolver $pathResolver,
        Yaml $yamlParser,
        string $settingsPath
    ) {
        /** @var \Doctrine\ORM\Mapping\ClassMetadata<\SprykerSdk\SdkContracts\Entity\SettingInterface> $class */
        $class = $entityManager->getClassMetadata(InfrastructureSetting::class);

        parent::__construct($entityManager, $class);
        $this->pathResolver = $pathResolver;
        $this->yamlParser = $yamlParser;
        $this->settingsPath = $settingsPath;
    }

    /**
     * @see \SprykerSdk\Sdk\Core\Application\Dependency\SettingFetcherInterface
     *
     * @param string $settingPath
     *
     * @throws \SprykerSdk\Sdk\Core\Application\Exception\SettingsNotInitializedException
     *
     * @return \SprykerSdk\SdkContracts\Entity\SettingInterface|null
     */
    public function findOneByPath(string $settingPath): ?SettingInterface
    {
        try {
            $setting = $this->findOneBy([
                'path' => $settingPath,
            ]);
        } catch (TableNotFoundException $e) {
            throw new SettingsNotInitializedException($e->getMessage(), 0, $e);
        }

        if (!$setting) {
            return null;
        }

        return $this->resolvePathSetting($setting);
    }

    /**
     * @see \SprykerSdk\Sdk\Core\Application\Dependency\SettingFetcherInterface
     *
     * @param string $settingPath
     *
     * @throws \SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException
     *
     * @return \SprykerSdk\SdkContracts\Entity\SettingInterface
     */
    public function getOneByPath(string $settingPath): SettingInterface
    {
        $setting = $this->findOneByPath($settingPath);

        if (!$setting) {
            throw new MissingSettingException(sprintf('Setting by path "%s" not found', $settingPath));
        }

        return $setting;
    }

    /**
     * @return array<\SprykerSdk\SdkContracts\Entity\SettingInterface>
     */
    public function findProjectSettings(): array
    {
        $settings = $this->createQueryBuilder('s')
            ->where('s.settingType <> :settingType')
            ->setParameter('settingType', Setting::SETTING_TYPE_SDK)
            ->getQuery()
            ->execute();

        return array_map([$this, 'resolvePathSetting'], $settings);
    }

    /**
     * @return array<\SprykerSdk\SdkContracts\Entity\SettingInterface>
     */
    public function findCoreSettings(): array
    {
        return $this->findBy([
            'settingType' => Setting::SETTING_TYPE_SDK,
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
     * @param \SprykerSdk\SdkContracts\Entity\SettingInterface $setting
     *
     * @return \SprykerSdk\SdkContracts\Entity\SettingInterface
     */
    public function save(SettingInterface $setting): SettingInterface
    {
        $this->getEntityManager()->persist($setting);
        $this->getEntityManager()->flush();

        return $setting;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\SettingInterface $setting
     *
     * @throws \SprykerSdk\Sdk\Infrastructure\Exception\InvalidTypeException
     *
     * @return \SprykerSdk\SdkContracts\Entity\SettingInterface|\SprykerSdk\Sdk\Infrastructure\Entity\Setting
     */
    protected function resolvePathSetting(SettingInterface $setting)
    {
        if (!$setting instanceof InfrastructureSetting) {
            throw new InvalidTypeException('Setting need to be of type ' . InfrastructureSetting::class);
        }
        if ($setting->getType() !== ValueTypeEnum::TYPE_PATH) {
            return $setting;
        }

        $values = $setting->getValues();
        if (is_array($values)) {
            foreach ($values as $key => $value) {
                $values[$key] = $this->resolvePathSettingByType($setting->getSettingType(), $value);
            }
        }

        if (is_string($values)) {
            $values = $this->resolvePathSettingByType($setting->getSettingType(), $values);
        }

        $setting->setValues($values);

        return $setting;
    }

    /**
     * @param string $settingType
     * @param mixed $values
     *
     * @return string
     */
    protected function resolvePathSettingByType(string $settingType, $values): string
    {
        if ($settingType === Setting::SETTING_TYPE_SDK) {
            return $this->pathResolver->getResolveRelativePath($values);
        }

        return $this->pathResolver->getResolveProjectRelativePath($values);
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\SettingInterface> $settings
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\SettingInterface>
     */
    public function saveMultiple(array $settings): array
    {
        foreach ($settings as $setting) {
            $this->save($setting);
        }

        return $settings;
    }

    /**
     * @return array<\SprykerSdk\SdkContracts\Entity\SettingInterface>
     */
    public function initSettingDefinition(): array
    {
        $settings = $this->yamlParser::parseFile($this->settingsPath, $this->yamlParser::PARSE_CONSTANT)['settings'] ?? [];
        $settingEntities = [];

        foreach ($settings as $setting) {
            $settingEntities[] = $this->getSettingEntityOrNew($setting);
        }

        return $settingEntities;
    }

    /**
     * @param array $setting
     *
     * @return \SprykerSdk\SdkContracts\Entity\SettingInterface
     */
    protected function getSettingEntityOrNew(array $setting): EntitySettingInterface
    {
        /** @var \SprykerSdk\Sdk\Infrastructure\Entity\Setting|null $settingEntity */
        $settingEntity = $this->findOneByPath($setting['path']);
        if ($settingEntity) {
            return $settingEntity;
        }

        $settingData = $this->prepereSettingData($setting);
        $settingEntity = new EntitySetting(
            null,
            $settingData['path'],
            $settingData['values'],
            $settingData['strategy'],
            $settingData['type'],
            $settingData['setting_type'],
            $settingData['init'],
            $settingData['force_ask_value'] ?? false,
            $settingData['initialization_description'],
            $settingData['initializer'],
        );

        $this->save($settingEntity);

        return $settingEntity;
    }

    /**
     * @param array $setting
     *
     * @return array
     */
    protected function prepereSettingData(array $setting): array
    {
        return [
            'path' => $setting['path'],
            'type' => $setting['type'] ?? ValueTypeEnum::TYPE_STRING,
            'setting_type' => $setting['setting_type'] ?? Setting::SETTING_TYPE_LOCAL,
            'initialization_description' => $setting['initialization_description'] ?? null,
            'strategy' => $setting['strategy'] ?? 'overwrite',
            'init' => $setting['init'] ?? false,
            'force_ask_value' => $setting['force_ask_value'] ?? false,
            'values' => $setting['values'],
            'initializer' => $setting['initializer'] ?? null,
        ];
    }
}
