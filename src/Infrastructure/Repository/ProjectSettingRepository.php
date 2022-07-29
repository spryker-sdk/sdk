<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository;

use SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Exception\MissingSettingException;
use SprykerSdk\Sdk\Core\Appplication\Service\PathResolver;
use SprykerSdk\Sdk\Infrastructure\Entity\Setting as InfrastructureSetting;
use SprykerSdk\Sdk\Infrastructure\Exception\InvalidTypeException;
use SprykerSdk\SdkContracts\Entity\SettingInterface;
use SprykerSdk\SdkContracts\Setting\SettingInitializerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Yaml;

class ProjectSettingRepository implements ProjectSettingRepositoryInterface
{
    /**
     * @var string
     */
    protected const LOCAL_PREFIX = 'local';

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected ContainerInterface $container;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface
     */
    protected SettingRepositoryInterface $coreSettingRepository;

    /**
     * @var \Symfony\Component\Yaml\Yaml
     */
    protected Yaml $yamlParser;

    /**
     * @var string
     */
    protected string $projectSettingFileName;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\PathResolver
     */
    protected PathResolver $pathResolver;

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface $coreSettingRepository
     * @param \Symfony\Component\Yaml\Yaml $yamlParser
     * @param string $projectSettingFileName
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\PathResolver $pathResolver
     */
    public function __construct(
        ContainerInterface $container,
        SettingRepositoryInterface $coreSettingRepository,
        Yaml $yamlParser,
        string $projectSettingFileName,
        PathResolver $pathResolver
    ) {
        $this->container = $container;
        $this->projectSettingFileName = $projectSettingFileName;
        $this->yamlParser = $yamlParser;
        $this->coreSettingRepository = $coreSettingRepository;
        $this->pathResolver = $pathResolver;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\SettingInterface $setting
     *
     * @return \SprykerSdk\SdkContracts\Entity\SettingInterface
     */
    public function save(SettingInterface $setting): SettingInterface
    {
        return $this->saveMultiple([$setting])[0];
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\SettingInterface> $settings
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\SettingInterface>
     */
    public function saveMultiple(array $settings): array
    {
        $localProjectValues = $this->getLocalProjectValues();
        $sharedProjectValues = $this->getSharedProjectValues();

        foreach ($settings as $setting) {
            if ($setting->getSettingType() === static::SHARED_SETTING_TYPE) {
                $sharedProjectValues[$setting->getPath()] = $setting->getValues();

                continue;
            }
            $localProjectValues[$setting->getPath()] = $setting->getValues();
        }

        if ($localProjectValues) {
            file_put_contents($this->projectSettingFileName, $this->yamlParser::dump($localProjectValues));
        }

        if ($sharedProjectValues) {
            file_put_contents($this->projectSettingFileName, $this->yamlParser::dump($sharedProjectValues));
        }

        return $settings;
    }

    /**
     * @param string $settingPath
     *
     * @return \SprykerSdk\SdkContracts\Entity\SettingInterface|null
     */
    public function findOneByPath(string $settingPath): ?SettingInterface
    {
        $coreSetting = $this->coreSettingRepository->findOneByPath($settingPath);
        if (!$coreSetting) {
            return $coreSetting;
        }

        $coreSetting = $this->resolvePathSetting($coreSetting);

        return $this->fillProjectValues([$coreSetting])[0];
    }

    /**
     * @param string $settingPath
     *
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\MissingSettingException
     *
     * @return \SprykerSdk\SdkContracts\Entity\SettingInterface
     */
    public function getOneByPath(string $settingPath): SettingInterface
    {
        $setting = $this->findOneByPath($settingPath);

        if (!$setting) {
            throw new MissingSettingException(sprintf('Setting by path "%s" not found. You need to run `sdk:init:project` command', $settingPath));
        }

        $initializer = $this->getSettingInitializer($setting);
        if ($initializer) {
            $initializer->initialize($setting);
        }

        return $setting;
    }

    /**
     * @return array<\SprykerSdk\SdkContracts\Entity\SettingInterface>
     */
    public function find(): array
    {
        $entities = $this->coreSettingRepository->findProjectSettings();
        foreach ($entities as $key => $entity) {
            $entities[$key] = $this->resolvePathSetting($entity);
        }

        if (!$entities) {
            return [];
        }

        return $this->fillProjectValues($entities);
    }

    /**
     * @return array<\SprykerSdk\SdkContracts\Entity\SettingInterface>
     */
    public function findProjectSettings(): array
    {
        return $this->find();
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\SettingInterface> $entities
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\SettingInterface>
     */
    protected function fillProjectValues(array $entities): array
    {
        $projectValues = $this->getProjectValues();

        foreach ($entities as $entity) {
            if ($entity->getSettingType() === static::SDK_SETTING_TYPE) {
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
        return array_merge($this->getSharedProjectValues(), $this->getLocalProjectValues());
    }

    /**
     * @return array
     */
    protected function getLocalProjectValues(): array
    {
        $localProjectSettingPath = $this->projectSettingFileName . '.' . static::LOCAL_PREFIX;

        if (!is_readable($localProjectSettingPath)) {
            return [];
        }

        return (array)$this->yamlParser::parseFile($localProjectSettingPath);
    }

    /**
     * @return array
     */
    protected function getSharedProjectValues(): array
    {
        $sharedProjectSettingPath = $this->projectSettingFileName;

        if (!is_readable($sharedProjectSettingPath)) {
            return [];
        }

        return (array)$this->yamlParser::parseFile($sharedProjectSettingPath);
    }

    /**
     * @return array<\SprykerSdk\SdkContracts\Entity\SettingInterface>
     */
    public function findCoreSettings(): array
    {
        return $this->coreSettingRepository->findCoreSettings();
    }

    /**
     * @return array<\SprykerSdk\SdkContracts\Entity\SettingInterface>
     */
    public function initSettingDefinition(): array
    {
        return $this->coreSettingRepository->initSettingDefinition();
    }

    /**
     * @param array<string> $paths
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\SettingInterface>
     */
    public function findByPaths(array $paths): array
    {
        return $this->fillProjectValues(
            $this->coreSettingRepository->findByPaths($paths),
        );
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\SettingInterface $setting
     *
     * @throws \SprykerSdk\Sdk\Infrastructure\Exception\InvalidTypeException
     *
     * @return \SprykerSdk\SdkContracts\Entity\SettingInterface
     */
    protected function resolvePathSetting(SettingInterface $setting)
    {
        if (!$setting instanceof InfrastructureSetting) {
            throw new InvalidTypeException('Setting need to be of type ' . InfrastructureSetting::class);
        }
        if ($setting->getType() === 'path') {
            $values = $setting->getValues();
            if (is_array($values)) {
                foreach ($values as $key => $value) {
                    $values[$key] = $this->pathResolver->getResolveProjectRelativePath($value);
                }
            }
            if (is_string($values)) {
                $values = $this->pathResolver->getResolveProjectRelativePath($values);
            }

            $setting->setValues($values);
        }

        return $setting;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\SettingInterface $setting
     *
     * @return \SprykerSdk\SdkContracts\Setting\SettingInitializerInterface|null
     */
    protected function getSettingInitializer(SettingInterface $setting): ?SettingInitializerInterface
    {
        $initializerId = $setting->getInitializer() ?? '';

        if (!$this->container->has($initializerId)) {
            return null;
        }

        $initializer = $this->container->get($initializerId);
        if (!$initializer instanceof SettingInitializerInterface) {
            return null;
        }

        return $initializer;
    }
}
